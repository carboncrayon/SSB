<?php

class ProfilePage extends Page
{

}

class ProfilePage_Controller extends Page_Controller
{
	static $allowed_actions = array(
		'show',
		'edit',
		'EditProfileForm'				
	);	

	public function init()
	{
		parent::init();

		Requirements::customScript(<<<JS

			jQuery(document).ready(function(){
				//match the latest column heights
				jQuery('.member-sidebar, .profile-details').equalHeight();
			});
JS
		);
	}

	/**
	 * get a member from URL params
	 */
	public function getSelectedMember()
	{
		if($this->request->params())
		{
			if(isset($params['ID']))
			{
				if(Member::get()->filter(array('Username'=>$params['ID']))->First())
				{
					return $member;
				}
			}
		}
	}

	/**
	 * set data for showing a specific member 
	 */
	public function show()
	{
		$Member = Member::currentUser();
		$SelectedMember = $this->getSelectedMember();
		
		if($Member && (!$SelectedMember || $Member->ID == $SelectedMember->ID))
		{
			$CustomData = array(
				'Member' => $Member,
				'Title' => 'Member Profile',
				'IsCurrentMember' => true,
				'Name' => array('Pronoun' => 'You', 'Adjective' => 'Your')
			);	
		}
		elseif($SelectedMember)
		{	
			$CustomData = array(
				'Member' => $SelectedMember,
				'Title' => 'Member Profile',
				'Name' => array('Pronoun' => $SelectedMember->Name, 'Adjective' =>  $SelectedMember->Name . "'s")
			);
		}	
		
		if(isset($CustomData))
		{			
			return $this->Customise($CustomData);	
		}
		
	}

	/**
	 * get the edit profile form 
	 * 
	 * @return EditProfileForm
	 */
	public function EditProfileForm()
	{
		return new EditProfileForm($this, 'EditProfileForm');
	}
}