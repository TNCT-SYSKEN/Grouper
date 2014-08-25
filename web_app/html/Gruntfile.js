'use strict';

module.exports = function(grunt) {
 	var pkg, taskName;
 	pkg = grunt.file.readJSON('package.json');
	grunt.initConfig({
		// compassのコンパイル
		compass: {
			dist: {
				options: {
					config: './config.rb'
				}
			}
		},
		// ファイル更新監視
		watch: {
			compass: {
				files: ['sass/*.scss'],
				tasks: ['compass:dist']
			}
		},
		// 簡易サーバ
		connect: {
			server: {
				options: {
					port: 7000,
					hostname: 'localhost'
				}
			}
		}
	});

	// GruntFile.jsに記載されているパッケージを自動読み込み
	for(taskName in pkg.devDependencies) {
		if(taskName.substring(0, 6) == 'grunt-') {
			grunt.loadNpmTasks(taskName);
		}
	}
	
	grunt.registerTask('default', ['connect', 'watch']);
	
	grunt.registerTask('eatwarnings', function() {
		grunt.warn = grunt.fail.warn = function(warning) {
			grunt.log.error(warning);
		};
	});

};
