const mix = require('laravel-mix');
const webpackNodeExternals = require('webpack-node-externals');

mix.options({ manifest: false })
    .js('resources/js/ssr.jsx', 'public/js')
    .react()
    .alias({
        '@': 'resources/js',
        ziggy: 'vendor/tightenco/ziggy/dist/index',
    })
    .webpackConfig({
        target: 'node',
        externals: [webpackNodeExternals()],
    });
