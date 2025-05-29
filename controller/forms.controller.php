<?php 

class FormsController {

    static public function AddUsers($data) {
        return FormsModel::mdlAddUsers($data);
    }
    
    static public function ctrSearchUsers($email) {
        return FormsModel::mdlSearchUsers($email);
    }

    static public function ctrAddFolder($folder, $idFolder, $idUser) {
        return FormsModel::mdlAddFolder($folder, $idFolder, $idUser);
    }

    static public function ctrGetFolders($idFolder) {
        return FormsModel::mdlGetFolders($idFolder);
    }

    static public function ctrFindFolder($idFolder) {
        return FormsModel::mdlFindFolder($idFolder);
    }

    static public function ctrUpdateFolder($folder, $idFolder) {
        return FormsModel::mdlUpdateFolder($folder, $idFolder);
    }

    public static function ctrGetFilesInFolder($folderId) {
        return FormsModel::mdlGetFilesInFolder($folderId);
    }

}