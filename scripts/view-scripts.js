/**
 * Created by Addison on 3/22/14.
 */
console.log("TEST");
$(document).ready(function(){

    $("newCommentReply").click(function(){
        $("#commentReply").toggle();
        console.log("click");
        // no page jump
        return false;
    });
});