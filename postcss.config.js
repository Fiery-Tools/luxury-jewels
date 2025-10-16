module.exports = {
  plugins: [
    // 1. Resolves and concatenates all the @import statements.
    require('postcss-import'),

    // 2. Minifies and optimizes the final CSS for production.
    require('cssnano')({
        preset: ['default', {
            // CRITICAL: Preserve all comments that start with '!'
            // This ensures the WordPress theme header comment (/*!...) is NOT removed.
            discardComments: {
                removeAll: true,
                preserve: /!/
            }
        }],
    }),
  ],
};