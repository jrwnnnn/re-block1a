<?php
require_once __DIR__ . '/../core/security-headers.php';

class userControllers {

    public function logout() {
        session_unset();
        session_destroy();

        header('Location: ../../index.php');
        exit();
    }

    public function deleteAccount() {
        $delete = query("DELETE FROM users WHERE uuid = ?", [$_SESSION['uuid']], "s"); 
        session_unset(); 
        session_destroy(); 
        header("Location: ../../index.php"); 
        exit();
    }

    public function updateProfile() {
        if (isset($_POST['banner_link'])) {
            $banner_link = trim($_POST['banner_link']);
            if ($banner_link === '') {
                $banner_link = NULL;
            }
        } else {
            $banner_link = NULL;
        }
        if ($banner_link !== NULL) {
            if (!filter_var($banner_link, FILTER_VALIDATE_URL)) {
                $_SESSION['rejected_banner_link'] = $banner_link;
                $_SESSION['banner_error'] = "Invalid URL format.";
                header('Location: ../settings.php');
                exit();
            } else {
                $check = @getimagesize($banner_link);
                if ($check === false) {
                    $_SESSION['rejected_banner_link'] = $banner_link;
                    $_SESSION['banner_error'] = "The provided link does not point to a valid image.";
                    header('Location: ../settings.php');
                    exit();
                }
            }
        }
        $update = query("UPDATE users SET bannerUrl = ? WHERE uuid = ?", [$banner_link, $_SESSION['uuid']], "ss");
        header('Location: ../settings.php');
        exit();
    }
    
    public function updatePrivacy() {
        $privateProfile = isset($_POST['privateProfile']) ? 1 : 0;
        $hideDeathLog = isset($_POST['hideDeathLog']) ? 1 : 0;

        $update = query("UPDATE users SET isPrivate = ?, hideDeathLog = ? WHERE uuid = ?", [$privateProfile, $hideDeathLog, $_SESSION['uuid']], "iis");

        header('Location: ../settings.php');
        exit();
    }
}