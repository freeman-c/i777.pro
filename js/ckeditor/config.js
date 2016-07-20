/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function(config) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
        // 	
	config.enterMode = CKEDITOR.ENTER_BR;
	config.shiftEnterMode = CKEDITOR.ENTER_P;	
	config.resize_enabled = true;
	config.htmlEncodeOutput = false;
	config.entities = false;
        config.extraPlugins = 'codemirror';
	//config.codemirror_theme = 'rubyblue';
        config.codemirror_theme = 'eclipse';
		config.codemirror = {
			theme: 'default',
			lineNumbers: true,
			lineWrapping: true,
			matchBrackets: true,
			autoCloseTags: true,
			autoCloseBrackets: true,
			enableSearchTools: true,
			enableCodeFolding: true,
			enableCodeFormatting: true,
			autoFormatOnStart: true,
			autoFormatOnModeChange: true,
			autoFormatOnUncomment: true,
			highlightActiveLine: true,
			mode: 'htmlmixed',
			showSearchButton: true,
			showTrailingSpace: true,
			highlightMatches: true,
			showFormatButton: true,
			showCommentButton: true,
			showUncommentButton: true,
			showAutoCompleteButton: true

		};
        
    config.contentsLangDirection = 'ltr';
    //config.contentsLangDirection = 'rtl';
	config.skin = 'moono';
	// config.skin = 'kama'; //moono
	//config.toolbar = 'full';
	config.toolbar = 'Custom';
 
config.toolbar_Custom =
[
   { name: 'code', items : [ 'Source' ]},
   { name: 'preview', items : [ 'Preview' ]},
   { name: 'tools', items : [ 'Maximize', 'ShowBlocks' ] },
   { name: 'editing', items : [ 'Find' ] },
   { name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule' ] },
   { name: 'links', items : [ 'Link','Unlink' ] },   
   { name: 'styles', items : [ 'Font','FontSize' ] },
   '/',
   { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike' ] },
   { name: 'colors', items : [ 'TextColor','BGColor' ] },
   { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] }
];
/*
config.toolbar_Custom =
[
   { name: 'code', items : [ 'Source' ]},
   { name: 'preview', items : [ 'Preview' ]},
   { name: 'tools', items : [ 'Maximize', 'ShowBlocks' ] },
   { name: 'document', items : [ 'Save','NewPage','DocProps','Print','-','Templates' ] },
   { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
   { name: 'forms', items : [ 'Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField' ] },   
   '/',
   { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
   { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
   { name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
   { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
   '/',
   { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
   { name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
   { name: 'colors', items : [ 'TextColor','BGColor' ] }
];*/
// ************* Smiley **************
config.smiley_path=CKEDITOR.basePath+'plugins/smiley/images/';
      config.smiley_images=['acute.gif','aggressive.gif','air_kiss.gif','angel.gif','bad.gif','bb.gif','beach.gif','beee.gif','biggrin.gif','big_boss.gif','blum.gif','blum3.gif','blush.gif','boast.gif','boredom.gif','buba.gif','bye.gif','clapping.gif','cray.gif','cray2.gif','crazy.gif','dance.gif','dance2.gif','dance3.gif','dance4.gif','dash2.gif','dash3.gif','declare.gif','diablo.gif','dirol.gif','don-t_mention.gif','download.gif','drinks.gif','feminist.gif','first_move.gif','focus.gif','fool.gif','friends.gif','gamer1.gif','girl_blum.gif','girl_cray.gif','girl_cray2.gif','girl_cray3.gif','girl_crazy.gif','girl_dance.gif','girl_devil.gif','girl_haha.gif','girl_hide.gif','girl_hospital.gif','girl_impossible.gif','girl_in_love.gif','girl_pinkglassesf.gif','girl_sad.gif','girl_wacko.gif','girl_wink.gif','girl_witch.gif','give_heart.gif','give_heart2.gif','give_rose.gif','good.gif','good2.gif','good3.gif','hang1.gif','hang2.gif','heart.gif','heat.gif','help.gif','hi.gif','hunter.gif','hysteric.gif','ireful1.gif','ireful3.gif','king.gif','kiss2.gif','kiss3.gif','laugh1.gif','laugh3.gif','lazy.gif','lol.gif','mail1.gif','man_in_love.gif','mda.gif','mega_shok.gif','moil.gif','mosking.gif','music.gif','nea.gif','negative.gif','new_russian.gif','ok.gif','on_the_quiet.gif','padonak.gif','paint3.gif','pardon.gif','party.gif','party2.gif','pilot.gif','pioneer.gif','pioneer_smoke.gif','pleasantry.gif','popcorm1.gif','preved.gif','punish.gif','rofl.gif','rtfm.gif','russian_ru.gif','sad.gif','santa2.gif','sarcastic.gif','sarcastic_blum.gif','sarcastic_hand.gif','scare.gif','scaut.gif','scratch_one-s_head.gif','search.gif','secret.gif','shok.gif','shout.gif','smoke.gif','sorry2.gif','spiteful.gif','stinker.gif','suicide2.gif','sun_bespectacled.gif','superstition.gif','tease.gif','thank_you2.gif','this.gif','to_become_senile.gif','to_pick_ones_nose2.gif','training1.gif','treaten.gif','unknw.gif','vava.gif','victory.gif','wacko.gif','wacko2.gif','whistle3.gif','wink.gif','wizard.gif','yahoo.gif','yes3.gif'];
config.smiley_descriptions=['acute','агрессия','воздушн. поцелуй','ангел','плохо','качёк','пляж','beee','biggrin','большой босс','язык1','язык2','краснею','boast','boredom','buba','пока','апплодисменты','плач1','плач2','сумашедший','танцую1','танцую2','танцую3','танцую4','dash2','dash3','declare','diablo','dirol','don-t_mention','download','drinks','feminist','first_move','focus','fool','friends','gamer1','girl_blum','girl_cray','girl_cray2','girl_cray3','girl_crazy','girl_dance','girl_devil','girl_haha','girl_hide','girl_hospital','girl_impossible','girl_in_love','girl_pinkglassesf','girl_sad','girl_wacko','girl_wink','girl_witch','give_heart','give_heart2','give_rose','good','good2','good3','hang1','hang2','heart','heat','help','hi','hunter','hysteric','ireful1','ireful3','king','kiss2','kiss3','laugh1','laugh3','lazy','lol','mail1','man_in_love','mda','mega_shok','moil','mosking','music','nea','negative','new_russian','ok','on_the_quiet','padonak','paint3','pardon','party','party2','pilot','pioneer','pioneer_smoke','pleasantry','popcorm1','preved','punish','rofl','rtfm','russian_ru','sad','santa2','sarcastic','sarcastic_blum','sarcastic_hand','scare','scaut','scratch_one-s_head','search','secret','shok','shout','smoke','sorry2','spiteful','stinker','suicide2','sun_bespectacled','superstition','tease','thank_you2','this','to_become_senile','to_pick_ones_nose2','training1','treaten','unknw','vava','victory','wacko','wacko2','whistle3','wink','wizard','yahoo','yes3'];
};
