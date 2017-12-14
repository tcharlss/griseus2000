// Inclure les dépendances
var
  gulp = require('gulp'),
  sass = require('gulp-sass'),
  autoprefixer = require('autoprefixer'),
  stripCssComments = require('gulp-strip-css-comments'),
  cssBase64 = require('gulp-css-base64'),
  cssbeautify = require('gulp-cssbeautify'),
  cleanCSS = require('gulp-clean-css'),
  plugins = require('gulp-load-plugins')(),
  postcss = require('gulp-postcss'),
  sourcemaps = require('gulp-sourcemaps'),
  rename = require('gulp-rename'),
  clean = require('gulp-clean')
;

// Surveiller certains fichiers et lancer une série de tâches
gulp.task('watch', function() {
  gulp.watch('./scss/**/*', gulp.series(
    'sass',
    'css'/*,
    'minify'*/
  ));
});

// Compiler le SASS en CSS
gulp.task('sass', function() {
  return gulp.src(['./scss/theme.scss'])
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./css'));
});

// Traiter le CSS compilé (compatibilité, optimisations...)
gulp.task('css', function() {
  return gulp.src('./css/theme.css')
    // Ajouter les préfixes navigateurs
    /*.pipe(postcss(
      [autoprefixer()]
    ))*/
    // Enlever les commentaires
    .pipe(stripCssComments())
    // Embarquer certaines ressources en base64
    /*.pipe(cssBase64({
      maxWeightResource: 131072,
      extensionsAllowed: ['.svg','.woff']
    }))*/
    // Formater le code
    .pipe(cssbeautify())
    .pipe(gulp.dest('./css'));
});

// Créer des copies minifiée des CSS
gulp.task('minify', function() {
  return gulp.src('./css/theme.css')
    .pipe(cleanCSS({
      compatibility: 'ie9'
    }))
    .pipe(rename({
      suffix: ".min"
    }))
    .pipe(gulp.dest('./css'));
});

// Importer les fichiers fontello à partir du fichier de config
gulp.task('fontello', function(done) {
  // importer les fichiers dans un dossier temporaire
  gulp.src('./scss/fontello-config.json')
    .pipe(plugins.fontello({
      font: 'font',
      css: 'css'
    }))
    .pipe(gulp.dest('./scss/fontello_tmp'));
  // renommer les fichiers importés
  gulp.src('./scss/fontello_tmp/font/icons.woff')
    .pipe(rename('./polices/fontello/fontello.woff'));
  gulp.src('./scss/fontello_tmp/css/icons-codes.css')
    .pipe(rename('./scss/modules/_fontello-codes.scss'));
  done();
});

// Tâche par défaut = watch
gulp.task('default', gulp.series('watch'));
