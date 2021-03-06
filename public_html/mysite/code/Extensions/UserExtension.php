<?php

class UserExtension extends DataExtension { 

    static $db = array(
		'Username' => 'Varchar(75)',
		'GithubName' => 'Varchar(100)',
		'TwitterName' => 'Varchar(25)',
		'LinkedInName' => 'Varchar(100)',
		'Website' => 'Varchar(255)',
		'Blurb' => 'Text',
      'JobTitle'=> 'Varchar(255)',
		'SnippetCount' => 'Int',
		'RetainContentOnDelete' => 'Boolean'
	);

	static $has_one = array(
		'Avatar' => 'Image'		
	);

	static $has_many = array(
		'Tutorials' => 'Tutorial',
		'Views' => 'View',
		'Snippets' => 'Snippet',
		'SOTMNominees' => 'SOTMNominee',
		'ArticleIdeas' => 'ArticleIdea',
		'ArticleVotes' => 'ArticleVote'		
	);
	
	public function updateCMSFields(FieldList $fields) 
	{	
		$fields->removeByName('SnippetCount');
		$fields->removeByName('ArticleVotes');
		$fields->removeByName('ArticleIdeas');

		$fields->addFieldToTab('Root.Main', new TextField('Username', 'Username'), 'Email');
		$fields->addFieldToTab('Root.Main', new TextField('Website', 'Website'), 'Locale');
		$fields->addFieldToTab('Root.Main', new TextareaField('Bio', 'Bio'), 'Locale');
		$fields->addFieldToTab('Root.Main', new CheckboxField('RetainContentOnDelete', 'Retain associated content on delete?'));

		//social media accounts
		$fields->addFieldToTab('Root.SocialMedia', new TextField('GithubName', 'Github name'));
		$fields->addFieldToTab('Root.SocialMedia', new TextField('TwitterName', 'Twitter Name'));
		$fields->addFieldToTab('Root.SocialMedia', new TextField('LinkedInName', 'LinkedIn Name'));

		//profile picture
		$fields->addFieldToTab('Root.Image', $imageField = new UploadField('Image', 'Image'));
		$imageField->setFolderName('Uploads/avatars');

		//grid fields to show posts
		$gridConfig = GridFieldConfig_RecordEditor::create();
		$gridField = new GridField('Tutorials', 'Tutorials', $this->owner->Tutorials(), $gridConfig);
		$fields->addFieldToTab('Root.Tutorials', $gridField);	

		$gridConfig = GridFieldConfig_RecordEditor::create();
		$gridField = new GridField('Views', 'Views', $this->owner->Views(), $gridConfig);
		$fields->addFieldToTab('Root.Views', $gridField);

		$gridConfig = GridFieldConfig_RecordEditor::create();
		$gridField = new GridField('Snippets', 'Snippets', $this->owner->Snippets(), $gridConfig);
		$fields->addFieldToTab('Root.Snippets', $gridField);

		$gridConfig = GridFieldConfig_RecordEditor::create();
		$gridField = new GridField('SOTMNominees', 'SOTM Nominees', $this->owner->SOTMNominees(), $gridConfig);
		$fields->addFieldToTab('Root.SOTMNominees', $gridField);

		$gridConfig = GridFieldConfig_RecordEditor::create();
		$gridField = new GridField('ArticleIdeas', 'Article Ideas', $this->owner->ArticleIdeas(), $gridConfig);
		$fields->addFieldToTab('Root.Ideas', $gridField);
   	}

   	/**
   	 * update the snippet count
   	 */
   	public function updateSnippetCount()
   	{
   		$numSnippets = Snippet::get()->filter(array('AuthorID' => $this->owner->ID))->count();
   		$this->owner->SnippetCount = $numSnippets;
   		$this->owner->write();
   	}

   	/**
   	 * get a link to the users profile page 
   	 * 
   	 * @return String
   	 */
   	public function getProfileLink()
   	{
   		if($profilePage = ProfilePage::get()->First())
   		{
   			return $profilePage->Link() . 'show/' . $this->owner->ID;
   		}
   	}

   	/**
   	 * ensure that any submitted content is deleted along with a member
   	 */
   	public function onBeforeDelete()
   	{
   		//remove content unless set to retain it
   		if(!$this->owner->RetainContentOnDelete)
   		{
   			//delete snippets
   			if($snips = $this->owner->Snippets())
   			{
   				foreach($snips as $snip)
   				{
                  $origStage = Versioned::current_stage();
                  $clone = clone $snip;
                  Versioned::reading_stage('Live');
   					$clone->delete();
                  Versioned::reading_stage($origStage);
                  $snip->delete();
   				}
   			}
   			//delete SOTM Nominees
   			if($nominees = $this->owner->SOTMNominees())
   			{
   				foreach($nominees as $nominee)
   				{
                  $origStage = Versioned::current_stage();
                  $clone = clone $nominee;
                  Versioned::reading_stage('Live');
                  $clone->delete();
                  Versioned::reading_stage($origStage);
                  $nominee->delete();
   				}
   			}
   			//delete ArticleIdeas
   			if($ideas = $this->owner->ArticleIdeas())
   			{
   				foreach($ideas as $idea)
   				{
                  $origStage = Versioned::current_stage();
                  $clone = clone $idea;
                  Versioned::reading_stage('Live');
                  $clone->delete();
                  Versioned::reading_stage($origStage);
                  $idea->delete();
   				}
   			}
   			//delete ArticleVotes
   			if($votes = $this->owner->ArticleVotes())
   			{
   				foreach($votes as $vote)
   				{
                  $origStage = Versioned::current_stage();
                  $clone = clone $vote;
                  Versioned::reading_stage('Live');
                  $clone->delete();
                  Versioned::reading_stage($origStage);
                  $vote->delete();
   				}
   			}
   			//delete Comments
   			if($comments = $this->owner->ItemComments())
   			{
   				foreach($comments as $comment)
   				{
                  $origStage = Versioned::current_stage();
                  $clone = clone $comment;
                  Versioned::reading_stage('Live');
                  $clone->delete();
                  Versioned::reading_stage($origStage);
                  $comment->delete();
   				}
   			}
   		}

   		parent::onBeforeDelete();
   	}

      /**
       * get the link to the users profile page 
       * 
       * @return String
       */
      public function Link()
      {
         if($profilePage = ProfilePage::get()->First())
         {
            return $profilePage->Link() . 'show/' . $this->owner->ID;
         }
      }
}