const flowbitePlugin = require("flowbite/plugin");

module.exports = {
    darkMode: "class",
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.vue",
        "./resources/**/*.js",
        "./node_modules/flowbite/**/*.js",
    ],
    plugins: [flowbitePlugin],
};
