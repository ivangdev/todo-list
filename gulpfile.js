import gulpSass from "gulp-sass";
import * as dartSass from "sass";
import gulpBabel from "gulp-babel";
import gulpPlumber from "gulp-plumber";
import { dest, src, watch, parallel } from "gulp";
import terser from "gulp-terser";

const sass = gulpSass(dartSass);

// Ubicación de los archivos scss y js
const paths = {
	scss: "src/scss/**/*.scss",
	js: "src/js/**/*.js",
};

// Tarea para compilar SCSS a CSS
export function css() {
	src(paths.scss, { sourcemaps: true })
		.pipe(gulpPlumber())
		.pipe(sass({ outputStyle: "compressed" }).on("error", sass.logError))
		.pipe(dest("public/dist/css"));
}

// JS compilador
export function js() {
	src(paths.js, { sourcemaps: true })
		.pipe(gulpPlumber())
		.pipe(gulpBabel({ presets: ["@babel/preset-env"] })) // Transpila el código JS
		.pipe(terser())
		.pipe(dest("public/dist/js"));
}

// Tarea para observar cambios en los archivos SCSS y JS
export function dev() {
	watch(paths.scss, css);
	watch(paths.js, js);
}
// Exportar las tareas para que puedan ser ejecutadas desde la línea de comandos
export default parallel(css, js, dev);
