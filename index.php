<?php
function startsWith ($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}
function endsWith($string, $endString)
{
    $len = strlen($endString);
    if ($len == 0) {
        return true;
    }
    return (substr($string, -$len) === $endString);
}
$strJsonFileContents = file_get_contents("people.json");
$nameArray = json_decode($strJsonFileContents, true);
$engNames = array_keys($nameArray);
$randomInt = rand(0, sizeof($engNames) - 1);
$possibleQuestion = isset($_POST['question']) ? $_POST['question'] : 'Nothing';
$possibleName = isset($_POST["person"]) ? $_POST['person'] : $nameArray[$engNames[$randomInt]];
$question = isset($_POST['question']) ? $_POST['question'] : 'Nothing';
// Open the file
$fp = @fopen('messages.txt', 'r'); 
// Add each line to an array
if ($fp) {
   $answers = explode("\n", fread($fp, filesize('messages.txt')));
   // echo abs(crc32 ($possibleQuestion . $possibleName)) % sizeof($answers);
}
$validQuestion = 1;
if (isset($_POST['person'])){
    if (! startsWith($_POST["question"], "آیا") || ! (endsWith($_POST["question"], "?") || endsWith($_POST["question"], "؟"))){
        $validQuestion = 0;
    }
}
$answers = file('messages.txt', FILE_IGNORE_NEW_LINES);
$possibleAnswerIndex = abs(crc32 ($possibleQuestion . $possibleName)) % sizeof($answers);
$msg = (isset($_POST['question'])) ? ($validQuestion) ? $answers[$possibleAnswerIndex] : "سوال درستی پرسیده نشده" : "سوال خود را بپرس!";
$en_name = isset($_POST["person"]) ? $_POST["person"] : $engNames[$randomInt];
$fa_name = $nameArray[$en_name];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>
<body>
<p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
<div id="wrapper">
    <?php 
        if (isset($_POST["person"])){
            echo "<div id='title'>
            <span id='label'>پرسش:</span>
            <span id='question'>$question</span>
        </div>";
        }
    ?>
    <div id="container">
        <div id="message">
            <p><?php echo $msg ?></p>
        </div>
        <div id="person">
            <div id="person">
                <img src="images/people/<?php echo "$en_name.jpg" ?>"/>
                <p id="person-name"><?php echo $fa_name ?></p>
            </div>
        </div>
    </div>
    <div id="new-q">
        <form action="index.php" method="post">
            سوال
            <?php
                if (isset($_POST["person"])){
                    echo "<input type='text' name='question' value='$question' placeholder='...'/>";
                } else{
                    echo "<input type='text' name='question' maxlength='150' placeholder='...'/>";
                }
            ?>
            
            را از
            <select name="person">
                <?php
                /*
                 * Loop over people data and
                 * enter data inside `option` tag.
                 * E.g., <option value="hafez">حافظ</option>
                 */
                    $strJsonFileContents = file_get_contents("people.json");
                    $nameArray = json_decode($strJsonFileContents, true);
                    $selected = "selected";
                    foreach($nameArray as $name => $persianName) {
                        if ($name == $en_name){
                            echo "<option selected=$selected value=$name>$persianName</option>";
                        } else {
                            echo "<option value=$name>$persianName</option>";
                        }
                    }
                ?>
            </select>
            <input type="submit" value="بپرس"/>
        </form>
    </div>
</div>
</body>
</html>