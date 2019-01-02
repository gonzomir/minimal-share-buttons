/*global module:false*/
module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    // Metadata.
    pkg: grunt.file.readJSON('package.json'),
    banner: '/*! <%= pkg.title || pkg.name %> - v<%= pkg.version %> - ' +
      '<%= grunt.template.today("yyyy-mm-dd") %>\n' +
      '<%= pkg.homepage ? "* " + pkg.homepage + "\\n" : "" %>' +
      '* Copyright (c) <%= grunt.template.today("yyyy") %> <%= pkg.author.name %>;' +
      ' Licensed <%= _.pluck(pkg.licenses, "type").join(", ") %> */\n',
    // Task configuration.
    uglify: {
      options: {
        mangle: false
      },
      default: {
        files: {
          'assets/js/msb.min.js': ['assets/js/svg4everybody.legacy.min.js', 'assets/js/minimal-share-buttons.js']
        }
      }
    },
    svgstore: {
      options: {
        prefix : 'icon-', // This will prefix each ID
        includedemo: true,
        cleanup: true,
        includeTitleElement: false,
        convertNameToId: function(name) {
          name = name.replace('ei-', '');
          return name;
        }
      },
      default : {
        files: {
          'assets/images/icons.svg': ['assets/images/icons/*.svg'],
        }
      }
    }
  });

  // These plugins provide necessary tasks.
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-svgstore');

  // Default task.
  grunt.registerTask('default', ['uglify', 'svgstore']);

};
