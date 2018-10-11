module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
      options: {
        banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> - v<%= pkg.version %> */\n'
      },
      build: {
        src: 'fingerprint.js',
        dest: 'build/fingerprint.min.js'
      }
    },
    jshint: {
      file: ['Gruntfile.js', 'fingerprint.js', 'specs/**/*_spec.js'],
      options: {
        eqnull: true,
        '-W086': true //W086: Expected a 'break' statement before 'case'.
      }
    }
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-jshint');

  // Default task(s).
  grunt.registerTask('default', ['jshint', 'uglify']);

};