module.exports = {
  plugins: [
    require('autoprefixer'),
    require('cssnano')({
      preset: 'default', // Using the default preset is often sufficient
    }),
  ],
};