const path = require("path");
   
module.exports = {
    mode: "development",
    entry: "./src/js/main.js", // входная точка - исходный файл
    output:{
        path: path.resolve(__dirname, "./public"),     // путь к каталогу выходных файлов - папка public
        publicPath: "/public/",
        filename: "index.js"       // название создаваемого файла
    },
    module:{
        rules:[   //загрузчик для js
            {
                test: /\.js?$/, // определяем тип файлов
                exclude: /(node_modules)/,  // исключаем из обработки папку node_modules
                loader: "babel-loader",   // определяем загрузчик
                options:{
                    presets:[ "@babel/preset-react"]    // используемые плагины
                }
            }
        ]
    }
}