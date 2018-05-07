CRM.$(function($) {

  type()
  match()

  // $("form[class^='CRM_Chat_Form_Action_'] .form-group").hide()
  // $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:type").show()
  // $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:match").show()

  $(document).on( "change", '[name=ChatAction\\:type]', type);
  $(document).on( "change", '[name=ChatAction\\:match]', match);

  function type(){

    $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:next").hide()
    $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:say").hide()
    $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:conversation").hide()
    $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:group").hide()
    $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:field").hide()

    val = $("[name=ChatAction\\:type]").val()

    if(val === 'next'){
      $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:next").show()
    }else if(val === 'say'){
      $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:say").show()
    }else if(val === 'conversation'){
      $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:conversation").show()
    }else if(val === 'group'){
      $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:group").show()
    }else if(val === 'field'){
      $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:field").show()
    }
  }

  function match(){

    $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:match_contains").hide()
    $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:match_equals").hide()

    val = $("[name=ChatAction\\:match]").val()

    if(val === 'CRM_Chat_Check_Contains'){
      $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:match_contains").show()
    }else if(val === 'CRM_Chat_Check_Equals'){
      $("form[class^='CRM_Chat_Form_Action_'] .ChatAction\\:match_equals").show()
    }
  }



  // controlAnswerPatternRaw();
  // controlActionData();
  //
  //
  //
  // function controlAnswerPatternRaw(){
  //   if($("[name=answer_pattern_type]").val() === 'anything' ){
  //     $("tr.answer_pattern_raw").hide();
  //   }else{
  //     $("tr.answer_pattern_raw").show();
  //   }
  //
  //   $(".answer-pattern-help").hide();
  //   $(".answer-pattern-help-' + $('[name=answer_pattern_type]").val()).show();
  //
  // }
  //
  // $("[name=_qf_Action_submit]").on( "click", function(){
  //   if($("select[name=answer_pattern_type]').val() === 'anything"){
  //     $("input[name=answer_pattern_raw]').val('/.*/");
  //   }
  // });
  //
  // $(document.body).on( "change", '[name=action_type]', controlActionData);
  //
  // function controlActionData(){
  //   action_type_class = 'action_type_' + $("[name=action_type]").val();
  //   $("tr.action_type").hide();
  //   $('tr.action_type.' + action_type_class).show();
  // }
});
