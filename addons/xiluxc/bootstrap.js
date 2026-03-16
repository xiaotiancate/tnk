if (Config.modulename == 'admin' || Config.modulename == 'index') {
    require.config({
        paths: {
            'vue': '../addons/xiluxc/js/vue.min'
        },
    });
}

