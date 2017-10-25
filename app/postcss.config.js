module.exports = {
    plugins: {
        // include whatever plugins you want
        // but make sure you install these via yarn or npm!

        'postcss-import': {},
        'stylelint': {},
        'postcss-reporter': { clearReportedMessages: true },
        'lost': {},
        // 'autoprefixer': {} // already included in postcss-cssnext
        'postcss-cssnext': {},
        'cssnano': {}, // cssnano has prefixes optimization which use autoprefixer
    }
}
