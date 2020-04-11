<?php 
if ($f == "add-blog-commreply") {
    $html = "";
    if (isset($_POST['text']) && isset($_POST['c_id']) && is_numeric(($_POST['c_id'])) && strlen($_POST['text']) > 2 && isset($_POST['b_id']) && is_numeric($_POST['b_id'])) {
        $registration_data = array(
            'comm_id' => Wo_Secure($_POST['c_id']),
            'blog_id' => Wo_Secure($_POST['b_id']),
            'user_id' => $wo['user']['id'],
            'text' => Wo_Secure($_POST['text']),
            'posted' => time()
        );
        $lastId            = Wo_RegisterBlogCommentReply($registration_data);
        if ($lastId && is_numeric($lastId)) {
            $comment = Wo_GetBlogCommentReplies(array(
                'id' => $lastId
            ));
            if ($comment && count($comment) > 0) {
                foreach ($comment as $wo['comm-reply']) {
                    $html .= Wo_LoadPage('blog/commreplies-list');
                }
                $data = array(
                    'status' => 200,
                    'html' => $html,
                    'comments' => Wo_GetBlogCommentsCount($_POST['b_id'])
                );
            }
        }
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
