<?php

namespace App\Controller;


use App\Entity\Section;
use App\Service\AdvertManager;
use App\Service\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * Show the list of all users for an admin user
     *
     * @Route("/admin/user-list",
     *     name="user_list")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param UserManager $userManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminShowUserList(UserManager $userManager)
    {
        $userList = $userManager->getUserList();

        return $this->render('admin/user_list.html.twig', ['userList' => $userList]);
    }

    /**
     * Delete an user from db in admin page
     *
     * @Route("/admin/delete-user/{user_id}",
     *     name="delete_user")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param UserManager $userManager
     * @param int $user_id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function adminDeleteUser(UserManager $userManager, int $user_id)
    {
        $userManager->removeUser($user_id);

        $this->addFlash('success', 'admin.deleteUser.validation');

        return $this->redirectToRoute('user_list');
    }

    /**
     * Show the list of all adverts for an admin user
     *
     * @Route("/admin/advert-list",
     *     name="advert_list")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param AdvertManager $advertManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminShowAdvertList(AdvertManager $advertManager)
    {
        $allAdverts = $advertManager->getAllAdvertsInfos();

        /**
         * @var Section $section
         */
        foreach ($advertManager->getAllSections() as $section) {
            $allSections[] = $section->getLabel();
        }

        return $this->render(
            'admin/advert_list.html.twig', [
                'advertList' => $allAdverts,
                'sections' => $allSections
            ]
        );
    }

    /**
     * Delete an advert from db through admin page
     *
     * @Route("/admin/delete-advert/{advert_id}",
     *     name="admin_delete_advert")
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param AdvertManager $advertManager
     * @param int $advert_id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function adminDeleteAdvert(AdvertManager $advertManager, int $advert_id)
    {
        $advertManager->removeAdvert($advert_id);

        $this->addFlash('success', 'admin.deleteAdvert.validation');

        return $this->redirectToRoute('advert_list');
    }
}
