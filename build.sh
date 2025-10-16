#!/bin/bash

# This script builds assets and packages the WordPress theme for distribution.

# --- Configuration ---
PLUGIN_SLUG="luxury-jewels"
ZIP_FILE="luxury-jewels.zip"
RELEASE_DIR="release"
HEADER_FILE="style.css"               # The file containing only the theme header (for version bumping)
SOURCE_CSS_FILE="src/index.css"       # The PostCSS import manifest
PROCESSED_CSS_FILE="style.processed.css" # Temporary PostCSS output (concatenated + minified)
FINAL_CSS_FILE="style.final.css"      # The final file with header + processed CSS

# Exit immediately if a command exits with a non-zero status.
set -e

# --- Build Process ---

# 1. Version Bumping
echo "🔍 Reading current version from $HEADER_FILE..."
# Get the line with the version number
VERSION_LINE=$(grep -i "Version:" $HEADER_FILE)
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
# Use a temporary file to support both macOS (BSD) and Linux sed
sed -i.bak "s/Version: .*/Version:       $NEW_VERSION/" $HEADER_FILE
# Remove the backup file created by sed
rm $HEADER_FILE.bak

# 2. Process CSS with PostCSS (Concatenate and Minify)
echo "🎨 Processing modular CSS from $SOURCE_CSS_FILE..."
# NOTE: PostCSS is configured to only concatenate and minify.
# It does NOT handle the header in this step.
npx postcss $SOURCE_CSS_FILE -o $PROCESSED_CSS_FILE

# 3. Combine Header and Processed CSS
echo "🔗 Combining theme header and processed CSS into $FINAL_CSS_FILE..."
# Prepend the theme header (style.css) content to the processed CSS (style.processed.css)
cat $HEADER_FILE $PROCESSED_CSS_FILE > $FINAL_CSS_FILE

# 4. Clean up from previous packaging attempts
echo "🧹 Cleaning up old zip files and release directories..."
rm -rf $RELEASE_DIR $ZIP_FILE

# 5. Create the release directory structure
echo "📂 Creating release directory..."
mkdir -p $RELEASE_DIR/$PLUGIN_SLUG

# 6. Copy only the production files into the release directory
echo "🚚 Copying production files..."
# Copy the entire theme structure
cp -r \
  includes \
  woocommerce \
  assets \
  js \
  styles \
  footer.php \
  front-page.php \
  functions.php \
  header.php \
  index.php \
  comments.php \
  page.php \
  readme.txt \
  screenshot.jpg \
  sidebar-shop.php* \
  woocommerce.php \
  $RELEASE_DIR/$PLUGIN_SLUG/

# Copy the final, concatenated CSS file as style.css
cp $FINAL_CSS_FILE $RELEASE_DIR/$PLUGIN_SLUG/style.css

# 7. Navigate into the release directory to create the zip
echo "🤐 Creating zip archive with correct structure..."
cd $RELEASE_DIR

# CRITICAL FIX: Zip the theme directory itself.
# This ensures the theme is inside the zip file's root directory.
zip -r ../$ZIP_FILE $PLUGIN_SLUG

# 8. Navigate back to the root and clean up
cd ..
echo "🧹 Cleaning up temporary release directory and processed CSS..."
rm -rf $RELEASE_DIR
rm $PROCESSED_CSS_FILE
rm $FINAL_CSS_FILE

echo "✅ Success! Theme packaged successfully: $ZIP_FILE"

# 9. Move to Downloads
mv $ZIP_FILE ~/Downloads