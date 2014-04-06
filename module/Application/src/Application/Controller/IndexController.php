<?php

namespace Application\Controller;

use Zend\View\Model\JsonModel;

class IndexController extends BaseActionController
{
    public function indexAction()
    {

    }

    public function faqAction()
    {
    }

    public function totalInfoAction()
    {
        /** @var $request \Zend\Http\Request */
        $request = $this->getRequest();

        if ($request->isXmlHttpRequest()) {
            /** @var \Vault\Service\Vaults $vaultService */
            $vaultService = $this->serviceLocator->get('Vault\VaultsService');
            /** @var \Share\Service\Shares $shareService */
            $shareService = $this->serviceLocator->get('Share\SharesService');

            $where = ['user_id=?' => $this->currentUser->id];
            $images = $vaultService->totalInfo($where);

            $userCount = 0;
            $linkCount = 0;

            if ($users = $this->usersService->getUserFriends($where['user_id=?'])) {
                $userCount = count($users);
            }

            if ($links = $shareService->getItem(['user_id=?' => $this->currentUser->id])) {
                $linkCount = $links->count();
            }

	        $storageSize = (2 * 1073741824) + (1048576 * $this->currentUser->bonus_storage);

            $summary =
            '<li><span class="label">' . $this->translate('Current pack') .
            ':</span> <span class="pack-name">LINKAFREE</span>
                </li><li><span class="label">' . $this->translate('Friends') .
            ':</span> <span class="friends-count">' . $userCount . '</span> (<a href="' . $this->url()->fromRoute('contacts') . '#add-contact" class="' .
            ((!$this->currentUser->is_confirmed) ? 'access-denied ' : '') . 'contact-add">' .
                $this->translate('Invite new Friends') . '</a>)</li>
                <li><span class="label">' .$this->translate('Expiration') . ':</span> <span class="pack-expiration">' .
	            date('d/m/Y', strtotime('+1 year', (int)$this->currentUser->cdate)) .'</span></li>
                <li><span class="label">' .$this->translate('Pending Moderation') . ':</span> N/A</li>
                <li><span class="label">' .$this->translate('Storage') . ':</span> <span class="storage">'.$this->prettyFileSize($storageSize).'</span></li>
                <li><span class="label">' .$this->translate('New shared pages') . ':</span> <span class="shared-pages-count">' .
            $linkCount . '</span></li>';
	        $factor = $storageSize / 100;
	        $storage = [
		        [
			        'key' => 'photos',
			        'label' => $this->translate('Photos'),
			        'weight' => $images['weight'],
			        'count' => $images['count'],
		        ],
	        ];

	        $freeSpacePercentage = 100;
	        $restOfStorage = $storageSize;
	        foreach ($storage as $index => $section) {
		        $storage[$index]['percentage'] = round($section['weight'] / $factor);
		        if ($storage[$index]['percentage'] == 0) $storage[$index]['percentage'] = 1;
		        elseif ($storage[$index]['percentage'] == 100) $storage[$index]['percentage'] = 99;

		        $restOfStorage -= $section['weight'];
		        $freeSpacePercentage -= $storage[$index]['percentage'];
	        }

	        array_push($storage, [
		        'key' => 'free',
		        'label' => 'Free',
		        'weight' => $restOfStorage,
		        'percentage' => $freeSpacePercentage,
	        ]);

	        $bar = '';
	        foreach ($storage as $section) {
		        $prettyWeight = $this->prettyFileSize($section['weight']);
		        $bar .= '<li class="' . $section['key'] . '" data-ratio="' . $section['percentage'] . '"><a href="#" title="'
				        . $section['label'] . '<br>'
				        . '<span>' . $prettyWeight . '</span><br>'
			            . (isset($section['count']) ? '<span>' . $section['count'] .'</span> Items' : '')
		            . '">' . $section['label'] . '</a></li>';
	        }

            return new JsonModel([
                'status' => true,
                'bar' => $bar,
                'summary' => $summary,
            ]);
        }

        $this->redirect()->toRoute('home');
    }

	private function prettyFileSize ($size) {
		$separators = ['b', 'Kb', 'Mb', 'Gb', 'Tb'];
		$sizeNext = $size;

		for ($i=0; $separator = $separators[$i++];) {
			$size = $sizeNext;
			$sizeNext = $size / 1024;
			if ($sizeNext < .5) break;
		}

		return round($size, 2) . ' ' . $separator;
	}
}