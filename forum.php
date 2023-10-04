<?php
require_once 'auth.php';
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>College Learning Forum</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            margin-top: 20px;
        }

        body {
            background-image: url("lock.jpg");
            background-repeat: no-repeat;
            background-position: fixed;
            background-size: cover;
            background-attachment: fixed;
        }

        input[type="submit"] {
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .text-center {
            text-align: center;
        }

        h1 {
            color: blueviolet;
        }

        h2 {
            color: red;
            text-align: center;
        }

        .questions {
            margin-top: 20px;
        }

        .question {
            padding: 10px;
            margin-bottom: 10px;
        }

        textarea {
            width: 100%;
            height: 100px;
        }

        .card-body {
            padding: 10px;
        }

        .card-footer {
            background-color: #f8f9fa;
            padding: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center">College Learning Forum</h1>

    <h2>Ask a Question</h2>
    <form action="submit_question.php" method="POST">
        <div class="form-group">
            <textarea class="form-control" name="question" placeholder="Type your question here" required></textarea>
        </div>
        <input type="submit" class="btn btn-primary" value="Submit Question">
    </form>

    <h2>Questions</h2>
    <div class="questions">
        <?php
        // Fetch and display questions from the database
        $servername = "localhost";
$username = "id20899253_raameele";
$password = "Jair@2002123";
$database = "id20899253_collegeforum";

        $conn = new mysqli($servername, $username, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM questions ORDER BY votes DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $questionNumber = 1;
            while ($row = $result->fetch_assoc()) {
                $questionId = $row['id'];
                $question = $row['question'];
                $votes = $row['votes'];
                $userId = $row['user_id'];
               
                $sqlUser = "SELECT username FROM users WHERE id = '$userId'";
                $resultUser = $conn->query($sqlUser);

                if ($resultUser->num_rows > 0) {
                    $rowUser = $resultUser->fetch_assoc();
                    $username = $rowUser['username'];
                    $createdDate = $row['created_date']; // Get the created date from the database
                    date_default_timezone_set('Asia/Kolkata');
                    $createdTime = date('H:i:s'); // Fetches the current time in the Indian timezone

                    echo '<div class="question card">';
                    echo '<div class="card-body">';
                    echo '<p class="card-text">Question #' . $questionNumber . '</p>';
                    echo '<p class="card-text">' . $question . '</p>';
                    $questionNumber++;
                    echo '<p class="card-text">Username: ' . $username . '</p>';
                    echo '<p class="card-text">Votes: ' . $votes . '</p>';
                  echo '<p class="card-text">Created: ' . $createdDate . ' ' . $createdTime . '</p>';


                    echo '<a href="upvote.php?id=' . $questionId . '" class="btn btn-primary">Upvote</a>';
                    echo '</div>';

                    // Fetch and display voters for the current question
                    $sqlVotes = "SELECT users.username FROM votes JOIN users ON votes.user_id = users.id WHERE votes.question_id = '$questionId' AND votes.vote_type = 'question' AND votes.user_id = '$userId'";
                    $resultVotes = $conn->query($sqlVotes);

                    if ($resultVotes->num_rows > 0) {
                        echo '<p>Voters: ';
                        while ($rowVote = $resultVotes->fetch_assoc()) {
                            echo $rowVote['username'] . ', ';
                        }
                        echo '</p>';
                    }

                    // Comment Form
                   // Comment Form
echo '<form action="submit_comment.php" method="POST">';
echo '<div class="form-group">';
echo '<input type="hidden" name="question_id" value="' . $questionId . '">';
echo '<input type="hidden" name="user_id" value="1">';
echo '<input type="text" class="form-control" name="comment" placeholder="Add a comment" required>';
echo '</div>';
echo '<input type="hidden" name="created_time" value="' . date('Y-m-d H:i:s') . '">'; // Include the created time value
echo '<input type="submit" class="btn btn-primary" value="Add Comment">';
echo '</form>';


                    // Fetch and display comments for the current question
                    $sqlComments = "SELECT * FROM comments WHERE question_id = '$questionId'";
                    $resultComments = $conn->query($sqlComments);

                    if ($resultComments->num_rows > 0) {
                        $commentNumber = 1;
                        while ($rowComment = $resultComments->fetch_assoc()) {
                            $commentId = $rowComment['id'];
                            $comment = $rowComment['comment'];
                            $commentVotes = $rowComment['votes'];
                            $userId = $rowComment['user_id'];

                            // Retrieve the username for the current user ID
                            $sqlUser = "SELECT username FROM users WHERE id = '$userId'";
                            $resultUser = $conn->query($sqlUser);

                            if ($resultUser->num_rows > 0) {
                                $rowUser = $resultUser->fetch_assoc();
                                $username = $rowUser['username'];
                                $createdDate = $rowComment['created_date']; // Get the created date from the database
                               date_default_timezone_set('Asia/Kolkata');
                               $createdTime = date('H:i:s'); // Fetches the current time in the Indian timezone

                                echo '<div class="card-footer">';
                                echo '<p class="card-text">Comment #' . $commentNumber . '</p>';
                                echo '<p class="card-text">' . $comment . '</p>';
                                $commentNumber++;
                                echo '<p class="card-text">Username: ' . $username . '</p>'; // Display the username
                                echo '<p class="card-text">Votes: ' . $commentVotes . '</p>';
                                echo '<p class="card-text">Created: ' . $createdDate . ' ' . $createdTime . '</p>'; 
                                echo '<a href="upvote_comment.php?id=' . $commentId . '" class="btn btn-primary">Upvote</a>';
                                echo '</div>';

                                // Fetch and display voters for the current comment
                                $sqlCommentVotes = "SELECT users.username FROM votes JOIN users ON votes.user_id = users.id WHERE votes.comment_id = '$commentId' AND votes.vote_type = 'comment' AND votes.user_id = '$userId'";
                                $resultCommentVotes = $conn->query($sqlCommentVotes);

                                if ($resultCommentVotes->num_rows > 0) {
                                    echo '<p>Comment Voters: ';
                                    while ($rowCommentVote = $resultCommentVotes->fetch_assoc()) {
                                        echo $rowCommentVote['username'] . ', ';
                                    }
                                    echo '</p>';
                                }
                            }
                        }
                    }
                    echo '</div>'; // Close the question card
                }
            }
        } else {
            echo "No questions found.";
        }

        $conn->close();
        ?>
    </div>
</div>
</body>
</html>