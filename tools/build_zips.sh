#!/usr/bin/env bash
# Builds the two packages to upload in WordPress (Wtyczki → Dodaj nową → Wyślij, Wygląd → Motywy → Dodaj nowy → Wyślij).
# Usage: tools/build_zips.sh   → dist/remontuj24-core.zip, dist/remontuj24.zip
set -euo pipefail
cd "$(dirname "$0")/.."
mkdir -p dist
rm -f dist/remontuj24-core.zip dist/remontuj24.zip
(cd wp-content/plugins && zip -qr ../../dist/remontuj24-core.zip remontuj24-core -x '*.DS_Store')
(cd wp-content/themes && zip -qr ../../dist/remontuj24.zip remontuj24 -x '*.DS_Store')
ls -lh dist/*.zip
