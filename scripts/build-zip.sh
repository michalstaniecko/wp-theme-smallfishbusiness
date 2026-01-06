#!/bin/bash

# Build ZIP archive for WordPress theme installation
# Usage: ./scripts/build-zip.sh

set -e

# Configuration
THEME_NAME="smallfishbusiness"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"
THEME_DIR="$PROJECT_DIR/themes/$THEME_NAME"
DIST_DIR="$PROJECT_DIR/dist"
VERSION=$(sed -n 's/^Version:[[:space:]]*\([0-9a-zA-Z.-]*\)/\1/p' "$THEME_DIR/style.css" 2>/dev/null || echo "1.0.0")
ZIP_NAME="${THEME_NAME}-${VERSION}.zip"

echo "=========================================="
echo "Building $THEME_NAME v$VERSION"
echo "=========================================="

# Step 1: Run production build
echo ""
echo "[1/4] Running production build..."
cd "$PROJECT_DIR"
npm run build

# Step 2: Create dist directory
echo ""
echo "[2/4] Preparing dist directory..."
rm -rf "$DIST_DIR"
mkdir -p "$DIST_DIR"

# Step 3: Create temporary directory for packaging
echo ""
echo "[3/4] Copying theme files..."
TEMP_DIR=$(mktemp -d)
TEMP_THEME_DIR="$TEMP_DIR/$THEME_NAME"
mkdir -p "$TEMP_THEME_DIR"

# Copy theme files (excluding dev files)
rsync -a \
    --exclude='.DS_Store' \
    --exclude='*.map' \
    --exclude='node_modules' \
    --exclude='.git' \
    --exclude='.gitignore' \
    --exclude='README.md' \
    "$THEME_DIR/" "$TEMP_THEME_DIR/"

# Step 4: Create ZIP archive
echo ""
echo "[4/4] Creating ZIP archive..."
cd "$TEMP_DIR"
zip -rq "$DIST_DIR/$ZIP_NAME" "$THEME_NAME"

# Cleanup
rm -rf "$TEMP_DIR"

# Summary
echo ""
echo "=========================================="
echo "Build complete!"
echo "=========================================="
echo "Output: dist/$ZIP_NAME"
echo "Size: $(du -h "$DIST_DIR/$ZIP_NAME" | cut -f1)"
echo ""
echo "Install via: WordPress Admin > Appearance > Themes > Add New > Upload Theme"
