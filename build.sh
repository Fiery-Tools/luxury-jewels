#!/bin/bash

# This script builds assets and packages the WordPress plugin for distribution.
# It creates a zip file with all files in the root, as required.

# --- Configuration ---
PLUGIN_SLUG="luxury-jewels"
ZIP_FILE="luxury-jewels.zip"
RELEASE_DIR="release"

# Exit immediately if a command exits with a non-zero status.
set -e

# --- Build Process ---


# 3. Clean up from previous packaging attempts
echo "🧹 Cleaning up old zip files and release directories..."
rm -rf $RELEASE_DIR $ZIP_FILE

# 4. Create the release directory structure
# NOTE: We now just create the parent release dir.
echo "📂 Creating release directory..."
mkdir -p $RELEASE_DIR

# 5. Copy only the production files into the release directory
echo "🚚 Copying production files..."
cp -r \
  includes \
  woocommerce \
  js \
  footer.php \
  front-page.php \
  functions.php \
  header.php \
  index.php \
  page.php \
  sidebar-shop.php* \
  woocommerce.php \
  style.css \
  $RELEASE_DIR/

# 6. Navigate into the release directory to create the zip
echo "🤐 Creating zip archive with correct structure..."
cd $RELEASE_DIR

# CRITICAL FIX: Zip all contents of the current directory ('.')
# The output path is one level up ('../') from our current location.
zip -r ../$ZIP_FILE .

# 7. Navigate back to the root and clean up
cd ..
echo "🧹 Cleaning up temporary release directory..."
rm -rf $RELEASE_DIR

echo "✅ Success! Plugin packaged successfully: $ZIP_FILE"

mv luxury-jewels.zip ~/Downloads