<?php
/*****************************************************************************
*	MainController.php
*
*	Author:  ClearHealth Inc. (www.clear-health.com)	2009
*	
*	ClearHealth(TM), HealthCloud(TM), WebVista(TM) and their 
*	respective logos, icons, and terms are registered trademarks 
*	of ClearHealth Inc.
*
*	Though this software is open source you MAY NOT use our 
*	trademarks, graphics, logos and icons without explicit permission. 
*	Derivitive works MUST NOT be primarily identified using our 
*	trademarks, though statements such as "Based on ClearHealth(TM) 
*	Technology" or "incoporating ClearHealth(TM) source code" 
*	are permissible.
*
*	This file is licensed under the GPL V3, you can find
*	a copy of that license by visiting:
*	http://www.fsf.org/licensing/licenses/gpl.html
*	
*****************************************************************************/


/**
 * Main controller
 */
class MainController extends WebVista_Controller_Action {
    protected $baseUrl;

    /**
     * Default action to dispatch
     */
    public function indexAction() {
        $this->baseUrl = Zend_Registry::get('baseUrl');
        $this->view->mainTabs = $this->getMainTabs();
	$this->view->activeTab = $this->getActiveTab();
    }

	public function setActiveTabAction() {
		$activeTab = $this->_getParam('activeTab');
		// if no active tab specified, use the default tab
		if (!strlen($activeTab) > 0) {
			$activeTab = $this->getActiveTab();
		}
		Menu::setCurrentlySelectedActivityGroup($activeTab);
		$data = array();
		$data['msg'] = __('Set successfully!');
		$json = Zend_Controller_Action_HelperBroker::getStaticHelper('json');
		$json->suppressExit = true;
		$json->direct($data);
	}

    private function userHasPermissionForTab($tabName) {
        return true;
    }

    private function getMainTabs() {
        $mainTabs = array();
        $mainTabs['Admin']['url']   = $this->view->baseUrl.'/admin.raw';
        $mainTabs['Admin']['hrefMode'] =  'ajax-html';

        foreach ($mainTabs as $tabName => $url) {
            if ($this->userHasPermissionForTab($tabName) === false) {
                unset($mainTabs[$tabName]);
            }
        }
        return $mainTabs;
    }
	private function getActiveTab() {
		$activeTab = 'Admin';
		Menu::setCurrentlySelectedActivityGroup($activeTab);
		return $activeTab;
	}
	static public function getActivePractice() {
                if (isset($_SESSION['defaultpractice'])) {
                        return (int)$_SESSION['defaultpractice'];
                }
                return 0;
        }

}

