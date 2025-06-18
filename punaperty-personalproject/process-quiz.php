<?php
$skill = $_POST['skill'];
$experience = (int)$_POST['experience'];
$type = $_POST['type'];

// Sample logic for directing
if ($skill == 'php' && $experience >= 2) {
    header("Location: jobs.php?filter=developer");
} elseif ($skill == 'design') {
    header("Location: jobs.php?filter=designer");
} elseif ($skill == 'marketing') {
    header("Location: jobs.php?filter=marketer");
} else {
    header("Location: jobs.php");
}
exit();
