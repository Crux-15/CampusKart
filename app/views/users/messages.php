<?php
    // --- GROUPING LOGIC ---
    // Group messages by the "Other Person" (Conversation Partner)
    $conversations = [];
    if (!empty($data['messages'])) {
        foreach ($data['messages'] as $msg) {
            // The "Other Person" ID comes from our smart Model query
            $partnerId = $msg->other_id;

            $conversations[$partnerId]['partner_info'] = [
                'name' => $msg->other_name,
                'image' => $msg->other_image,
                'id' => $msg->other_id
            ];
            
            // We also need the Product ID for the reply form
            $conversations[$partnerId]['product_id'] = $msg->product_id;

            // Add message to this conversation
            $conversations[$partnerId]['messages'][] = $msg;
        }
    }

    // --- SORTING LOGIC (NEW) ---
    // Sort conversations so the one with the MOST RECENT message is at the top
    uasort($conversations, function($a, $b) {
        // Get the last message (newest) from conversation A and B
        $lastMsgA = end($a['messages']);
        $lastMsgB = end($b['messages']);
        
        // Compare times: Newer time (larger value) goes first
        return strtotime($lastMsgB->created_at) - strtotime($lastMsgA->created_at);
    });
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Messages - CampusKart</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/homepageStyles.css">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/inboxStyles.css">
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <a href="<?php echo URLROOT; ?>/pages/index" class="logo">CampusKart</a>
            <ul class="nav-links">
                <li><a href="<?php echo URLROOT; ?>/pages/index">Back to Home</a></li>
            </ul>
            <div class="user-actions">
                <div class="profile-dropdown">
                    <?php if(!empty($data['user']->profile_image)): ?>
                        <img src="<?php echo URLROOT; ?>/img/profiles/<?php echo $data['user']->profile_image; ?>" 
                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid white; cursor: pointer;">
                    <?php else: ?>
                        <div class="avatar-circle">
                            <?php echo substr($data['user']->fullname, 0, 1); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="dropdown-menu">
                        <div class="user-info">
                            <strong><?php echo $data['user']->fullname; ?></strong>
                            <small>Student</small>
                        </div>
                        <hr>
                        <a href="<?php echo URLROOT; ?>/users/account" class="menu-item"><span class="icon">&#128100;</span> Account</a>
                        <a href="<?php echo URLROOT; ?>/products/listings" class="menu-item"><span class="icon">&#128230;</span> My Listings</a>
                        <a href="<?php echo URLROOT; ?>/users/messages" class="menu-item"><span class="icon">&#128172;</span> Messages</a>
                        <a href="<?php echo URLROOT; ?>/products/notifications" class="menu-item"><span class="icon">&#128276;</span> Notifications</a>
                        <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin'): ?>
                            <a href="<?php echo URLROOT; ?>/admin/index" class="menu-item" style="color: red; font-weight: bold; background-color: #fff0f0;">
                                <span class="icon">&#128736;</span> Admin Panel
                            </a>
                            <hr>
                        <?php endif; ?>
                        <hr>
                        <a href="<?php echo URLROOT; ?>/users/logout" class="menu-item logout"><span class="icon">&#128682;</span> Sign Out</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="inbox-container">
        
        <div class="inbox-header">
            <h2>Inbox</h2>
            <p>Your conversations grouped by sender</p>
        </div>

        <?php if(empty($conversations)): ?>
            <div style="text-align:center; padding:50px; color:#777;">
                <h3>No messages yet</h3>
            </div>
        <?php else: ?>
            
            <?php foreach($conversations as $partnerId => $chat): ?>
            
            <div class="conversation-card">
                
                <div class="conversation-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="sender-profile">
                        <?php if(!empty($chat['partner_info']['image'])): ?>
                            <img src="<?php echo URLROOT; ?>/img/profiles/<?php echo $chat['partner_info']['image']; ?>" class="convo-avatar">
                        <?php else: ?>
                            <div class="avatar-circle small">
                                <?php echo substr($chat['partner_info']['name'], 0, 1); ?>
                            </div>
                        <?php endif; ?>
                        <h3 class="convo-name"><?php echo $chat['partner_info']['name']; ?></h3>
                    </div>

                    <form action="<?php echo URLROOT; ?>/users/delete_conversation" method="POST" class="delete-form">
                        <input type="hidden" name="partner_id" value="<?php echo $chat['partner_info']['id']; ?>">
                        <button type="submit" class="btn-delete" title="Delete Conversation">
                            &#128465; </button>
                    </form>
                </div>


                <div class="conversation-body">
                    <?php foreach($chat['messages'] as $msg): ?>
                        
                        <?php $isMe = ($msg->sender_id == $_SESSION['user_id']); ?>

                        <div class="msg-row <?php echo $isMe ? 'me' : 'them'; ?>">
                            <div class="msg-bubble">
                                <div class="msg-meta">
                                    <span class="product-badge"><?php echo $msg->product_title; ?></span>
                                    <span><?php echo date('M d, h:i A', strtotime($msg->created_at)); ?></span>
                                </div>
                                <?php echo nl2br($msg->message); ?>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>

                <div class="reply-box">
                    <form action="<?php echo URLROOT; ?>/users/reply" method="POST" class="reply-form">
                        <input type="hidden" name="receiver_id" value="<?php echo $chat['partner_info']['id']; ?>">
                        <input type="hidden" name="product_id" value="<?php echo $chat['product_id']; ?>">
                        
                        <input type="text" name="message_body" class="reply-input" placeholder="Type a reply..." required>
                        <button type="submit" class="reply-btn">Send</button>
                    </form>
                </div>
                
            </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>


    <script src="<?php echo URLROOT; ?>/js/inbox.js?v=<?php echo time(); ?>"></script>

</body> 

</body>
</html>