/*
 * Block editor for Remontuj24 sections. Every block is described by fields in PHP
 * (blocks/<name>/def.php); this file turns them into sidebar controls and shows a live
 * server-side preview in the canvas. Also adds the "Dane realizacji" and "SEO" panels.
 * Plain ES2017 without a build step, using the wp.* globals.
 */
(function (wp) {
  const { createElement: h, Fragment, useState } = wp.element;
  const { registerBlockType } = wp.blocks;
  const { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } = wp.blockEditor;
  const { PanelBody, TextControl, TextareaControl, ToggleControl, SelectControl, Button, Placeholder, Notice } = wp.components;
  const ServerSideRender = wp.serverSideRender;
  const { useSelect } = wp.data;

  const defs = window.r24Blocks || {};

  /* ---------- media ---------- */

  function useMedia(id) {
    return useSelect(select => (id ? select('core').getMedia(id, { context: 'view' }) : null), [id]);
  }

  function MediaThumb({ id }) {
    const media = useMedia(id);
    if (!id) return null;
    const url = media && (media.media_details && media.media_details.sizes && media.media_details.sizes.medium ? media.media_details.sizes.medium.source_url : media.source_url);
    return url ? h('img', { src: url, alt: '', className: 'r24-thumb' }) : h('div', { className: 'r24-thumb is-loading' });
  }

  function ImageField({ field, value, onChange }) {
    return h('div', { className: 'r24-field r24-image' },
      h('p', { className: 'r24-label' }, field.label),
      h(MediaThumb, { id: value }),
      h(MediaUploadCheck, null,
        h(MediaUpload, {
          allowedTypes: ['image'],
          value: value || undefined,
          onSelect: m => onChange(m.id),
          render: ({ open }) => h('div', { className: 'r24-row' },
            h(Button, { variant: value ? 'secondary' : 'primary', onClick: open }, value ? 'Zmień zdjęcie' : 'Wybierz zdjęcie'),
            value ? h(Button, { variant: 'tertiary', isDestructive: true, onClick: () => onChange(0) }, 'Usuń') : null
          )
        })
      ),
      field.help ? h('p', { className: 'r24-help' }, field.help) : null
    );
  }

  function GalleryField({ field, value, onChange }) {
    const ids = Array.isArray(value) ? value : [];
    return h('div', { className: 'r24-field r24-gallery' },
      h('p', { className: 'r24-label' }, field.label),
      h('div', { className: 'r24-gallery-grid' }, ids.map(id => h(MediaThumb, { key: id, id }))),
      h(MediaUploadCheck, null,
        h(MediaUpload, {
          allowedTypes: ['image'],
          multiple: true,
          gallery: true,
          value: ids,
          onSelect: items => onChange(items.map(m => m.id)),
          render: ({ open }) => h(Button, { variant: 'secondary', onClick: open }, ids.length ? 'Edytuj galerię' : 'Dodaj zdjęcia')
        })
      )
    );
  }

  /* ---------- repeater ---------- */

  function emptyItem(fields) {
    const item = {};
    fields.forEach(f => { item[f.key] = 'default' in f ? f.default : (f.type === 'image' ? 0 : ''); });
    return item;
  }

  function RepeaterField({ field, value, onChange }) {
    const items = Array.isArray(value) ? value : [];
    const [open, setOpen] = useState(-1);
    const update = (i, key, v) => onChange(items.map((it, k) => (k === i ? Object.assign({}, it, { [key]: v }) : it)));
    const move = (i, d) => {
      const next = items.slice();
      const j = i + d;
      if (j < 0 || j >= next.length) return;
      [next[i], next[j]] = [next[j], next[i]];
      onChange(next);
      setOpen(j);
    };
    const remove = i => {
      // eslint-disable-next-line no-alert
      if (window.confirm('Usunąć ten element?')) onChange(items.filter((_, k) => k !== i));
    };
    return h('div', { className: 'r24-field r24-repeater' },
      h('p', { className: 'r24-label' }, field.label),
      items.map((item, i) => {
        const title = (item[field.itemLabel] || '').toString().trim() || `Element ${i + 1}`;
        const isOpen = open === i;
        return h('div', { key: i, className: 'r24-item' + (isOpen ? ' is-open' : '') },
          h('div', { className: 'r24-item-head' },
            h('button', { type: 'button', className: 'r24-item-title', onClick: () => setOpen(isOpen ? -1 : i), 'aria-expanded': isOpen },
              h('span', { className: 'r24-item-no' }, String(i + 1).padStart(2, '0')), title),
            h(Button, { icon: 'arrow-up-alt2', label: 'W górę', size: 'small', onClick: () => move(i, -1), disabled: i === 0 }),
            h(Button, { icon: 'arrow-down-alt2', label: 'W dół', size: 'small', onClick: () => move(i, 1), disabled: i === items.length - 1 }),
            h(Button, { icon: 'trash', label: 'Usuń', size: 'small', isDestructive: true, onClick: () => remove(i) })
          ),
          isOpen ? h('div', { className: 'r24-item-body' },
            field.fields.map(sub => h(Field, { key: sub.key, field: sub, value: item[sub.key], onChange: v => update(i, sub.key, v) }))
          ) : null
        );
      }),
      h(Button, { variant: 'secondary', className: 'r24-add', onClick: () => { onChange(items.concat([emptyItem(field.fields)])); setOpen(items.length); } }, '+ Dodaj')
    );
  }

  /* ---------- one field ---------- */

  function Field({ field, value, onChange }) {
    const common = { label: field.label, help: field.help, __nextHasNoMarginBottom: true };
    switch (field.type) {
      case 'textarea':
        return h('div', { className: 'r24-field' }, h(TextareaControl, Object.assign({}, common, { value: value || '', onChange, rows: Math.min(8, Math.max(3, Math.ceil(((value || '').length) / 42))) })));
      case 'url':
        return h('div', { className: 'r24-field' }, h(TextControl, Object.assign({}, common, { value: value || '', onChange, placeholder: '/kontakt/', help: field.help || 'Adres strony, np. /kontakt/ albo pełny link.' })));
      case 'number':
        return h('div', { className: 'r24-field' }, h(TextControl, Object.assign({}, common, { type: 'number', value: value === undefined ? '' : value, onChange: v => onChange(v === '' ? 0 : Number(v)) })));
      case 'toggle':
        return h('div', { className: 'r24-field' }, h(ToggleControl, Object.assign({}, common, { checked: !!value, onChange })));
      case 'select':
        return h('div', { className: 'r24-field' }, h(SelectControl, Object.assign({}, common, {
          value: value || field.default || '',
          options: Object.keys(field.options || {}).map(k => ({ value: k, label: field.options[k] })),
          onChange
        })));
      case 'image':
        return h(ImageField, { field, value, onChange });
      case 'gallery':
        return h(GalleryField, { field, value, onChange });
      case 'repeater':
        return h(RepeaterField, { field, value, onChange });
      default:
        return h('div', { className: 'r24-field' }, h(TextControl, Object.assign({}, common, { value: value || '', onChange })));
    }
  }

  /* Splits the field list into sidebar panels at every { type: 'panel' } marker. */
  function panels(fields) {
    const out = [{ label: 'Treść', fields: [] }];
    fields.forEach(f => {
      if (f.type === 'panel') out.push({ label: f.label, fields: [] });
      else out[out.length - 1].fields.push(f);
    });
    return out.filter(p => p.fields.length);
  }

  /* ---------- blocks ---------- */

  Object.keys(defs).forEach(name => {
    const def = defs[name];
    registerBlockType(name, {
      edit: function Edit({ attributes, setAttributes, name: blockName }) {
        const blockProps = useBlockProps({ className: 'r24-block-preview' });
        const postId = useSelect(select => select('core/editor') && select('core/editor').getCurrentPostId(), []);
        const groups = panels(def.fields);
        const filled = def.fields.some(f => {
          const v = attributes[f.key];
          return f.type !== 'panel' && f.type !== 'toggle' && f.type !== 'select' && f.type !== 'number' && v && (!Array.isArray(v) || v.length);
        });
        const empty = () => h(Placeholder, { icon: 'layout', label: wp.blocks.getBlockType(blockName).title, instructions: 'Uzupełnij pola w panelu po prawej. Podgląd pojawi się tutaj.' });
        return h(Fragment, null,
          h(InspectorControls, null,
            def.hint ? h(Notice, { status: 'info', isDismissible: false }, def.hint) : null,
            groups.map((g, i) => h(PanelBody, { key: g.label, title: g.label, initialOpen: i === 0 },
              g.fields.map(f => h(Field, { key: f.key, field: f, value: attributes[f.key], onChange: v => setAttributes({ [f.key]: v }) }))
            ))
          ),
          h('div', blockProps,
            filled || !def.fields.some(f => f.type === 'text' || f.type === 'textarea')
              ? h(ServerSideRender, {
                block: blockName,
                attributes,
                httpMethod: 'POST',
                urlQueryArgs: postId ? { post_id: postId } : {},
                EmptyResponsePlaceholder: empty
              })
              : empty()
          )
        );
      },
      save: () => null
    });
  });

  /* ---------- document panels: project facts and SEO ---------- */

  const plugins = wp.plugins;
  const DocPanel = (wp.editor && wp.editor.PluginDocumentSettingPanel) || (wp.editPost && wp.editPost.PluginDocumentSettingPanel);
  if (!plugins || !DocPanel) return;
  const { useEntityProp } = wp.coreData;

  function MetaFields({ fields, prefix }) {
    const postType = useSelect(select => select('core/editor').getCurrentPostType(), []);
    const [meta, setMeta] = useEntityProp('postType', postType, 'meta');
    if (!meta) return null;
    return h(Fragment, null, fields.map(f => h(Field, {
      key: f.key,
      field: f,
      value: meta[prefix + f.key],
      onChange: v => setMeta(Object.assign({}, meta, { [prefix + f.key]: v }))
    })));
  }

  plugins.registerPlugin('r24-realizacja', {
    render: function RealizacjaPanel() {
      const postType = useSelect(select => select('core/editor').getCurrentPostType(), []);
      if (postType !== 'realizacja') return null;
      return h(DocPanel, { name: 'r24-realizacja', title: 'Dane realizacji', className: 'r24-doc-panel' },
        h('p', { className: 'r24-help' }, 'Zdjęcie na liście ustawisz w panelu „Zdjęcie na liście”. Gdy jest zdjęcie „przed”, karta pokazuje je po najechaniu.'),
        h(MetaFields, { fields: window.r24Realizacja || [], prefix: 'r24_' })
      );
    }
  });

  plugins.registerPlugin('r24-seo', {
    render: function SeoPanel() {
      const postType = useSelect(select => select('core/editor').getCurrentPostType(), []);
      if (['post', 'page', 'realizacja'].indexOf(postType) === -1) return null;
      return h(DocPanel, { name: 'r24-seo', title: 'SEO', className: 'r24-doc-panel' },
        h(MetaFields, {
          prefix: 'r24_seo_',
          fields: [
            { key: 'title', label: 'Tytuł w Google (do ok. 60 znaków)', type: 'text', help: 'Puste = tytuł strony i nazwa witryny.' },
            { key: 'desc', label: 'Opis w Google (do ok. 155 znaków)', type: 'textarea', help: 'Puste = zajawka wpisu.' }
          ]
        })
      );
    }
  });
})(window.wp);
