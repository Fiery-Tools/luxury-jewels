#!/bin/bash

# This script builds assets and packages the WordPress plugin for distribution.
# It creates a zip file with all files in the root, as required.

# --- Configuration ---
PLUGIN_SLUG="luxury-jewels"
ZIP_FILE="luxury-jewels.zip"
RELEASE_DIR="release"
CSS_FILE="style.css"
PROCESSED_CSS_FILE="style.processed.css"

# Exit immediately if a command exits with a non-zero status.
set -e

# --- Build Process ---

# 1. Version Bumping
echo "🔍 Reading current version from $CSS_FILE..."
# Get the line with the version number
VERSION_LINE=$(grep -i "Version:" $CSS_FILE)
# Extract the version number (e.g., 1.0.0)
CURRENT_VERSION=$(echo $VERSION_LINE | grep -o '[0-9.]*')
# Split version into parts
MAJOR=$(echo $CURRENT_VERSION | cut -d. -f1)
MINOR=$(echo $CURRENT_VERSION | cut -d. -f2)
PATCH=$(echo $CURRENT_VERSION | cut -d. -f3)
# Increment the patch number
NEW_PATCH=$((PATCH + 1))
# Assemble the new version string
NEW_VERSION="$MAJOR.$MINOR.$NEW_PATCH"
echo "⬆️  Bumping version from $CURRENT_VERSION to $NEW_VERSION..."
# Replace the old version with the new one in style.css
# This works on both macOS (BSD) and Linux sed.
sed -i.bak "s/Version: .*/Version:       $NEW_VERSION/" $CSS_FILE
# Remove the backup file created by sed
rm $CSS_FILE.bak

# 2. Process CSS with PostCSS
echo "🎨 Processing CSS with PostCSS..."
npx postcss $CSS_FILE -o $PROCESSED_CSS_FILE

# 3. Clean up from previous packaging attempts
echo "🧹 Cleaning up old zip files and release directories..."
rm -rf $RELEASE_DIR $ZIP_FILE

# 4. Create the release directory structure
echo "📂 Creating release directory..."
mkdir -p $RELEASE_DIR

# 5. Copy only the production files into the release directory
echo "🚚 Copying production files..."
# Note: We are now copying the processed CSS file and renaming it
cp -r \
  includes \
  woocommerce \
  assets \
  js \
  footer.php \
  front-page.php \
  functions.php \
  header.php \
  index.php \
  page.php \
  screenshot.jpg \
  sidebar-shop.php* \
  woocommerce.php \
  $PROCESSED_CSS_FILE \
  $RELEASE_DIR/

# Rename the processed CSS back to the original name in the release directory
mv $RELEASE_DIR/$PROCESSED_CSS_FILE $RELEASE_DIR/$CSS_FILE

# 6. Navigate into the release directory to create the zip
echo "🤐 Creating zip archive with correct structure..."
cd $RELEASE_DIR

# CRITICAL FIX: Zip all contents of the current directory ('.')
# The output path is one level up ('../') from our current location.
zip -r ../$ZIP_FILE .

# 7. Navigate back to the root and clean up
cd ..
echo "🧹 Cleaning up temporary release directory and processed CSS..."
rm -rf $RELEASE_DIR
rm $PROCESSED_CSS_FILE

echo "✅ Success! Plugin packaged successfully: $ZIP_FILE"

mv luxury-jewels.zip ~/Downloads