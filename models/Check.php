<?php
namespace models;

use models\pages\PageModel;

class Check
{
    private $userRole;

    public function __construct($userRole)
    {
        $this->userRole = $userRole;
    }

    public function getCurrentUrlSlug(){
        $url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $parsedUrl = parse_url($url);
        $path = $parsedUrl['path'];

        $segments = explode('/',ltrim($path, '/') );
        $firstTwoSegments = array_slice($segments, 0, 2);
        $slug = implode('/', $firstTwoSegments);
        return $slug;
    }

    public function checkPermission($slug)
    {
        // Получить информацию о странице по slug
        $pageModel = new PageModel();
        $page = $pageModel->findBySlug($slug);
        if (!$page) {
            return false;
        }
        // Получить разрешенные роли для страницы
        $allowedRoles = explode(',', $page['role']);
        // Проверить, имеет ли текущий пользователь доступ к странице
        if (isset($this->userRole) && in_array($this->userRole, $allowedRoles)) {
            return true;
        } else {
            return false;
        }       
    }

    public function requirePermission()
    {
        if(!ENABLE_PERMISSION_CHECK){
            return;
        }
        
        $slug = $this->getCurrentUrlSlug();

        if (!$this->checkPermission($slug)) {
            header("Location: /");
            exit();
        }
    }

    public function isCurrentUserRole($role)
    {
        return $this->userRole == $role;
    }

}
