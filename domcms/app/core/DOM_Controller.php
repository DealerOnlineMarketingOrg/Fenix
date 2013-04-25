<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
  This is our main controller
  This controller checks the user credentials.
  If the users credentials do not pass it sends them to the login page.
 */
session_start();

class DOM_Controller extends CI_Controller {

    //All we need is the construct and all controllers will pass through this on every page.

    public $user;
    public $LevelView;
    public $man_nav;
    public $user_nav;
    public $avatar;
    public $selected_agency;
    public $selected_group;
    public $selected_client;
    public $theme_settings;
    public $validser;
	public $TagCss;
	public $TagHTML;
	public $activeNav;
	

    public function __construct() {
        parent::__construct();
		//load custom error handling
		//set_exception_handler('topExceptionHandler');

		//force secure content
		$this->load->helper('securecontent');
		$protocol = get_protocol();
		
		if((ENVIRONMENT == 'production') AND ($protocol != 'https')) {
			redirect('https://content.dealeronlinemarketing.com','refresh');
		}
		
        $this->load->helper('template');
        $this->load->library('gravatar');
        $this->load->model('mods');
		$this->load->model('utilities');
		$this->load->helper('err_helper');
		$this->load->library('session');
		$this->load->model('members');

		$this->TagCss  = $this->utilities->getTagCss();
		$this->TagHTML = $this->utilities->getTagSelect();

        $this->theme_settings = array(
			'ThemeViews'	=> FCPATH . 'html/theme/',
			'GlobalViews'	=> FCPATH . 'html/global/',
			'CSSDIR'		=> $this->config->item('CSSDIR'),
			'JSDIR'			=> $this->config->item('JSDIR'),
			'IMGSDIR'		=> $this->config->item('IMGSDIR'),
            'ThemeDir' 		=> $this->config->item('ThemeDir'),
            'GlobalDir' 	=> $this->config->item('GlobalDir'),
            'Title' 		=> $this->config->item('Title'),
            'CompanyName' 	=> $this->config->item('CompanyName'),
            'AppName' 		=> $this->config->item('AppName'),
            'Logo' 			=> '<img src="' . base_url() . $this->config->item('Logo') . '" alt="' . $this->config->item('CompanyName') . '" />',
            'AppVersion' 	=> $this->config->item('AppVersion'),
            'GapiEmail' 	=> $this->config->item('GapiEmail'),
            'GapiPass' 		=> $this->config->item('GapiPass'),
            'GoogleFonts' 	=> $this->config->item('GoogleFonts'),
            'DocType' 		=> $this->config->item('DocType'),
            'HTML' 			=> $this->config->item('HTML'),
            'MetaTags' 		=> $this->config->item('MetaTags'),
            'Files' 		=> $this->config->item('Files'),
            'Breadcrums' 	=> $this->config->item('Breadcrumbs'),
			'TagCss' 		=> $this->TagCss,
			'Publisher'		=> $this->config->item('CityGridPublisher')
        );
		
        //Active button sets the highlighted icon on the view
        $active_button = $this->router->fetch_class();
		//if the page is on one of the authentication pages, we dont need the rest.
		if($active_button != 'login' && $active_button != 'logout' && $active_button != 'authenticate') :
		

			$current_subnav_button = $this->uri->rsegment(2); // The Function 
			define('ACTIVE_BUTTON', $active_button);
			define('SUBNAV_BUTTON', '/' . $active_button . '/' . $current_subnav_button);
	
			$this->user = $this->session->userdata('valid_user');
			$this->avatar = $this->members->get_user_avatar($this->user['UserID']);
	
			//This checks the user validation
			$this->validUser = ($this->session->userdata('valid_user')) ? TRUE : FALSE;
			if (!$this->validUser) {
				redirect('login');
			}
			
			$this->user['DropdownDefault']->SelectedAgency = (($this->user['DropdownDefault']->SelectedAgency) ? $this->user['DropdownDefault']->SelectedAgency : $this->user['AgencyID']);
			$this->user['DropdownDefault']->SelectedGroup  = (($this->user['DropdownDefault']->SelectedGroup)  ? $this->user['DropdownDefault']->SelectedGroup  : $this->user['GroupID']);
			$this->user['DropdownDefault']->SelectedClient = (($this->user['DropdownDefault']->SelectedClient) ? $this->user['DropdownDefault']->SelectedClient : $this->user['ClientID']);
	
			if (!isset($this->session->userdata['err'])) {
				// Create err object as empty array and store in session.
				$err = array('err' => array());
				$this->session->set_userdata($err);
				$this->err = $this->session->userdata['err'];
			} else {
				$this->err = $this->session->userdata['err'];
				
				// Reduce lifetime of errors and clear out any
				//  that have gone past end-of-life.
				ageError();
				clearError();
			}
			
			$this->session->sess_write();
			
			$this->load->model('nav');
			$this->main_nav = $this->nav->main($this->user['AccessLevel']);
			$this->user_nav = $this->nav->user($this->user['AccessLevel']);
		endif;
		
    }
	
	public function GoogleCSRFToken() {
		$state = md5(rand());
		$app['session']->set('state', $state);
		// Set the client ID, token state, and application name in the HTML while
		// serving it.
		return $app['twig']->render(base_url(), array(
			'CLIENT_ID' => '170027429160.apps.googleusercontent.com',
			'STATE' => $state,
			'APPLICATION_NAME' => 'DomCMS'
		  ));	
	}

    public function LoadTemplate($filepath, $data = false, $header_data = false, $nav_data = false, $footer_data = false) {
		
		//Get what page we are currently on, we need this to load the pieces we need.
		$page = $this->router->fetch_class();		

		// Define what level were on so we can filter the naviation and take away the items that arn't available on certain levels
		
        $this->load->dom_view('incl/header', $this->theme_settings['GlobalViews'], $this->theme_settings);
		//if were not on the login screen, show the user nav
        if($page != 'login' && $page != 'sign_in') { 
			$user_nav = array(
				'nav' => $this->user_nav,
				'user' => $this->user,
				'avatar' => $this->avatar,
			);
			$this->load->dom_view('incl/user_nav', $this->theme_settings['GlobalViews'], $user_nav); 
		}
		
		//if were not on the login screen, show the header
        if($page != 'login' && $page != 'sign_in') { 
			$header_data = array(
				'tagHtml' => $this->TagHTML
			);
			$this->load->dom_view('incl/header', $this->theme_settings['ThemeViews'], ($header_data) ? $header_data : array()); 
		}
		
		//if were not on the login screen, show the nav
        if($page != 'login' && $page != 'sign_in') { 
			$level = $this->user['DropdownDefault']->LevelType;
			
			switch($level) {
				case 'a':
					$selectedLevel = 1;
				break;	
				case 'g':
					$selectedLevel = 2;
				break;
				case 'c':
					$selectedLevel = 3;
				break;
				default :
					$selectedLevel = 1;
				break;
			}
			$nav = array(
				'nav' => $this->main_nav,
				'active_button' => $this->activeNav,
				'activeLevel' => $selectedLevel
			);
			$this->load->dom_view('incl/nav', $this->theme_settings['GlobalViews'] . $nav);
		}
		
		//load the content
        $this->load->dom_view($filepath, $this->theme_settings['ThemeViews'] , ($data) ? $data : array());
		
		//if were not on the login screen, load the tags form in the footer. Its hidden by default.
		if($page != 'login' && $page != 'sign_in') { 
			$this->load->dom_view('forms/ajax/add_tags', $this->theme_settings['ThemeViews']);
		}
		
		//Load the footers
        $this->load->dom_view('incl/footer', $this->theme_settings['ThemeViews'], ($footer_data) ? $footer_data : array());
        $this->load->dom_view('incl/footer', $this->theme_settings['GlobalViews']);
		
    }

    public function generateLevelName($level) {
        switch ($level) {
            case 'g':
                return '<span class="title">Group Name:</span>';
                break;
            case 'c':
                return '<span class="title">Client Name:</span>';
                break;

            case 'a':
                return '<span class="title">Agency Name:</span>';
                break;
            default:
                return '<span class="title">Agency Name:</span>';
                break;
        };
    }

    //Gets the access level of the user.
    public function generateLevelNumber($level) {
        $loggedInUserLevel = $this->user['AccessLevel'];
        if ($loggedInUserLevel >= 600000) {
            switch ($level) {
                case 'g' :
                    return 400000;
                    break;
                case 'c' :
                    return 300000;
                    break;
                case 2 :
                    return 400000;
                    break;
                case 3 :
                    return 300000;
                    break;
                default :
                    return 600000;
                    break;
            }
        } else if ($loggedInUserLevel < 600000 AND $loggedInUserLevel >= 500000) {
            switch ($level) {
                case 'g' :
                    return 400000;
                    break;
                case 'c' :
                    return 300000;
                    break;
                case 2:
                    return 400000;
                    break;
                case 3:
                    return 300000;
                    break;
                default :
                    return 500000;
                    break;
            }
        } else if ($loggedInUserLevel < 500000 AND $loggedInUserLevel >= 400000) {
            switch ($level) {
                case 'c':
                    return 300000;
                    break;
                case 3:
                    return 300000;
                    break;
                default :
                    return 400000;
                    break;
            }
        } else if ($loggedInUserLevel < 400000 AND $loggedInUserLevel >= 300000) {
            switch ($level) {
                default:
                    return 300000;
                    break;
            }
        } else if ($loggedInUserLevel < 300000 AND $loggedInUserLevel >= 200000) {
            switch ($level) {
                default:
                    return 200000;
                    break;
            }
        } else {
            return 100000;
        }
    }

    //This checks to see if the user has permissions to the specific module
    public function CheckModule($module_name) {
		return GateKeeper($module_name,$this->user['AccessLevel']);
    }

    //custom 404 page
    public function Page_Not_Found() {
        $this->LoadTemplate('pages/errors/404');
    }

    //Access Denied
    public function AccessDenied() {
        $this->LoadTemplate('pages/access_denied');
    }

    //This tells me what level the user is currently logged in as.
    public function DisplaySettings() {
        $display_session = $this->user['DropdownDefault'];
        $level = substr($display_session->SelectedID, 0, 1);
        //Readable way to tell what level were on.
        if ($level == 'a') {
            $this->LevelView = 'Agency';
        } elseif ($level == 'g') {
            $this->LevelView = 'Group';
        } elseif ($level == 'c') {
            $this->LevelView = 'Client';
        } else {
            //if super admin
            $this->LevelView = 'Agency';
            //if group admin group level
        }
    }


    public function Form_processor($page, $which) {
        $this->load->helper('template');
        $this->load->model('members');
        $this->load->model('administration');
		$this->load->model('rep');
		
        switch ($page) :
			case "clients":
							
				switch($which) :
					case "add":
					
						$form = $this->input->post();
						$data = array(
							'GROUP_ID' => (($this->user['DropdownDefault']->SelectedGroup) ? $this->user['DropdownDefault']->SelectedGroup : 1),
							'CLIENT_Name'    => $form['ClientName'],
							'CLIENT_Address' => 'street:'   . $form['street'] . 
												',city:'    . $form['city'] . 
												',state:'   . $form['state'] . 
												',zipcode:' . $form['zip'],
							'CLIENT_Phone'   => 'main:' . $form['phone'],
							'CLIENT_Notes'   => $form['Notes'],
							'CLIENT_Code'    => $form['ClientCode'],
							'CLIENT_Active'  => $form['Status'],
							'CLIENT_Created' => date('Y-m-d H:i:s'),
							'CLIENT_Tag'     => $form['tags']
						);
						//add posted client to database
						$addClient = $this->administration->addClient($data);
						
						if($addClient) {
							$review = array(
								array(
									'ClientID' => $this->user['DropdownDefault']->SelectedClient,
									'ServicesID' => 1,
									'URL' => $form['GoogleReviewURL']
								),array(
									'ClientID' => $this->user['DropdownDefault']->SelectedClient,
									'ServicesID' => 2,
									'URL' => $form['YelpReviewURL']
								),array(
									'ClientID' => $this->user['DropdownDefault']->SelectedClient,
									'ServicesID' => 3,
									'URL' => $form['YahooReviewURL']
								)
							);
							
							$addGoogleReview = $this->administration->addReview($review[0]);
							$addYelpReview = $this->administration->addReview($review[1]);
							$addYahooReview = $this->administration->addReview($review[2]);
						}
						
						
						throwError(newError('Clients Add',
											($addClient) ? 1 : -1,
											($addClient) ? 'The Client was added successfully!'
														 : 'Something went wrong. Please try again or contact your admin.',
											0, ''));
						redirect('/admin/clients/add','refresh');
					break;
					case "edit":
						$form = $this->input->post();
						$client_id = $form['ClientID'];
						
						
						$data = array(
							'GROUP_ID' 		 => (($this->user['DropdownDefault']->SelectedGroup)?$this->user['DropdownDefault']->SelectedGroup:1),
							'CLIENT_Name'    => $form['ClientName'],
							'CLIENT_Address' => 'street:'   . $form['street'] . 
												',city:'    . $form['city']   . 
												',state:'   . $form['state']  . 
												',zipcode:' . $form['zip'],
							'CLIENT_Phone'   => 'main:' . $form['phone'],
							'CLIENT_Notes'   => $form['Notes'],
							'CLIENT_Code'    => $form['ClientCode'],
							'CLIENT_Active'  => $form['Status'],
							'CLIENT_Created' => date('Y-m-d H:i:s'),
						);
						
						$reviews = array();
						
						if($form['GoogleID'] == 0) {
							$google = array(
								'ClientID' => $form['ClientID'],
								'ServicesID' => 1,
								'URL' => $form['GoogleReviewURL'],
							);
							$addGoogleReview = $this->administration->addReview($google);
							array_push($reviews,$addGoogleReview);
						}else {
							$google = array(
								'ID' => $form['GoogleID'],
								'ClientID' => $form['ClientID'],
								'ServicesID' => 1,
								'URL' => $form['GoogleReviewURL'],
							);
							$updateGoogleReview = $this->administration->editReviews($google,$client_id,1);
							array_push($reviews,$updateGoogleReview);
						}
						
						if($form['YelpID'] == 0) {
							$yelp = array(
								'ClientID' => $form['ClientID'],
								'ServicesID' => 2,
								'URL' => $form['YelpReviewURL']
							);
							$addYelpReview = $this->administration->addReview($yelp);
							array_push($reviews,$addYelpReview);
						}else {
							$yelp = array(
								'ID' => $form['YelpID'],
								'ClientID' => $form['ClientID'],
								'ServicesID' => 2,
								'URL' => $form['YelpReviewURL']
							);
							$updateYelpReview = $this->administration->editReviews($yelp,$client_id,2);
							array_push($reviews,$updateYelpReview);
						}
						
						if($form['YahooID'] == 0 && $form['YahooReviewURL'] != '') {
							$yahoo = array(
								'ClientID' => $form['ClientID'],
								'ServicesID' => 3,
								'URL' => $form['YahooReviewURL']
							);
							$addYahooReview = $this->administration->addReview($yahoo);
							array_push($reviews,$addYahooReview);
						}else {
							$yahoo = array(
								'ID' => $form['YahooID'],
								'ClientID' => $form['ClientID'],
								'ServicesID' => 3,
								'URL' => $form['YahooReviewURL']
							);
							$updateYahooReview = $this->administration->editReviews($yahoo,$client_id,3);
							array_push($reviews,$updateYahooReview);
						}
						
						$updateClient = $this->administration->editClient($data, $this->input->post('ClientID'));
						
						throwError(newError('Clients Edit',
											($updateClient) ? 1 : -1,
											($updateClient) ? 'The Client was edited successfully!'
															: 'Something went wrong. Please try again or contact your admin.',
											0, ''));
						if($form['Status'] == '0')
							$this->reset_dd_session('/clients/edit');
						else
							redirect('/clients','refresh');
					break;
				endswitch;
			break;
			case "groups":
				switch($which):
					case "add":
						$form = $this->input->post();
						$selectedAgencyId = $this->user['DropdownDefault']->SelectedAgency;
						$group_name = $form['GroupName'];
						$group_notes = $form['Description'];
						$group_status = $form['Status'];
						
						$data = array(
							'GROUP_Name' => $group_name,
							'GROUP_Notes' => $group_notes,
							'AGENCY_ID' => $selectedAgencyId,
							'GROUP_Active' => $group_status,
							'GROUP_Created' => date('Y-m-d H:i:s'),
						);
						
						//print_object($data);
						$add = $this->administration->addGroup($data);
						
						throwError(newError('Groups Add',
											($add) ? 1 : -1,
											($add) ? 'Your Group was added successfully!'
												   : 'Something went wrong. Please try again or contact your admin.',
											0, ''));
						redirect('/admin/groups/add','refresh');
					break;
					case "edit":
						$form = $this->input->post();
						$selectedAgencyId = $this->user['DropdownDefault']->SelectedAgency;
						$group_id = $form['GroupID'];
						$group_name = $form['GroupName'];
						$group_notes = $form['Description'];
						$group_status = $form['Status'];
						
						$data = array(
							'GROUP_ID' => $group_id,
							'GROUP_Name' => $group_name,
							'GROUP_Notes' => $group_notes,
							'AGENCY_ID' => $selectedAgencyId,
							'GROUP_Active' => $group_status,
							'GROUP_Created' => date('Y-m-d H:i:s'),
						);
						
						//print_object($data);
						$edit = $this->administration->editGroup($data);
						
						throwError(newError('Groups Edit',
											($edit) ? 1 : -1,
											($edit) ? 'Your Group was edited successfully!'
													: 'Something went wrong. Please try again or contact your admin.',
											0, ''));
						if($group_status == '0')
							$this->reset_dd_session('/admin/groups/edit');
						else
							redirect('/admin/groups/edit','refresh');
					break;
				endswitch;
			break;
            case "contacts":
                switch($which) :
                    case "add":
                        $form = $this->input->post();
                        $type = $form['type'] .':' . $this->user['DropdownDefault']->SelectedClient;
                        $firstname = $form['firstname'];
                        $lastname =  $form['lastname'];
                        $address = 'street:' . $form['street'] . ',city:' . $form['city'] . ',state:' . $form['state'] . ',zipcode:' . $form['zip'];
                        $notes = $form['notes'];
						
						$email  = 'home:' . $this->input->post('PersonalEmailAddress') . 
						  (($this->input->post('WorkEmailAddress')) ? 
							',work:' . $this->input->post('WorkEmailAddress') : 
						  '');
						  
						$phone  = 'main:' . $this->input->post('DirectPhone') . (($this->input->post('MobilePhone')) ? ',mobile:' . $this->input->post('MobilePhone') : '') . (($this->input->post('FaxPhone')) ? ',fax:' . $this->input->post('FaxPhone') : '');

                        
                        $data = array(
                            'TITLE_ID' => 12,
                            'DIRECTORY_Type' => $type,
                            'DIRECTORY_FirstName' => $firstname,
                            'DIRECTORY_LastName' => $lastname,
                            'DIRECTORY_Address' => $address,
                            'DIRECTORY_Email' => $email,
                            'DIRECTORY_Phone' => $phone,
                            'DIRECTORY_Notes' => $notes,
                            'DIRECTORY_Created' => date(FULL_MILITARY_DATETIME),
							'JobTitle'=>$form['JobTitle'],
							'CLIENT_Owner'=>$this->user['DropdownDefault']->SelectedClient
                        );
                        
                        $addContact = $this->administration->addContact($data);
                        
						throwError(newError('Contacts Add',
											($addContact) ? 1 : -1,
											($addContact) ? 'Your Contact was added successfully!'
														  : 'Something went wrong. Please try again or contact your admin.',
											0, ''));
						redirect('/admin/contacts/add','refresh');                         
                    break;
					case "edit":
                        $form      = $this->input->post();

						$email     = 'home:' . $this->input->post('PersonalEmailAddress') . (($this->input->post('WorkEmailAddress')) ? ',work:' . $this->input->post('WorkEmailAddress') : '');
						$phone     = 'main:' . $this->input->post('DirectPhone') . (($this->input->post('MobilePhone')) ? ',mobile:' . $this->input->post('MobilePhone') : '') . (($this->input->post('FaxPhone')) ? ',fax:' . $this->input->post('FaxPhone') : '');
                        $type      = $form['type'] .':' . $this->user['DropdownDefault']->SelectedClient;
                        $firstname = $form['firstname'];
                        $lastname  =  $form['lastname'];
                        $address   = 'street:' . $form['street'] . ',city:' . $form['city'] . ',state:' . $form['state'] . ',zipcode:' . $form['zip'];
                        $notes     = $form['notes'];
                        
                        $data = array(
                            'TITLE_ID' => 12,
                            'DIRECTORY_Type' => $type,
                            'DIRECTORY_FirstName' => $firstname,
                            'DIRECTORY_LastName' => $lastname,
                            'DIRECTORY_Address' => $address,
                            'DIRECTORY_Email' => $email,
                            'DIRECTORY_Phone' => $phone,
                            'DIRECTORY_Notes' => $notes,
                            'DIRECTORY_Created' => date(FULL_MILITARY_DATETIME),
							'JobTitle'=>$form['JobTitle'],
							'CLIENT_Owner'=> $form['company']
                        );
                        
                        $editContact = $this->administration->updateContact($data, $this->input->post('contact_id'));
                        
						throwError(newError('Contacts Edit',
											($editContact) ? 1 : -1,
											($editContact) ? 'Your Contact was edited successfully!'
														   : 'Something went wrong. Please try again or contact your admin.',
											0, ''));
                        redirect('/admin/contacts/edit','refresh');
                        
					break;
                endswitch;
            break;
            case "agency":
                switch ($which):
                    case "add":
                        //create array from port post elements
                        $form = $this->input->post();
                        $add = $this->administration->addAgencies($form);
						
						/*
						throwError(newError('Agency Add',
											($add) ? 1 : -1,
											($add) ? 'Your Agency was added successfully!'
													: 'Something went wrong. Please try again or contact your admin.',
											0, ''));
						redirect('/admin/agency/add', 'location');
						*/
                    break;
                    case "edit":
                        $form = $this->input->post();

                        $id = $form['AGENCY_ID'];
                        $data = array(
                            'AGENCY_Name' => $form['AGENCY_Name'],
                            'AGENCY_Notes' => $form['AGENCY_Notes'],
                            'AGENCY_Active' => $form['AGENCY_Active']
                                //'AGENCY_Created' => $form['AGENCY_Created']
                        );

                        $edit = $this->administration->updateAgencyInformation($id, $data);

						throwError(newError('Agency Edit',
											($edit) ? 1 : -1,
											($edit) ? 'Your Agency was edited successfully!'
													: 'Something went wrong. Please try again or contact your admin.',
											0, ''));
						redirect('/admin/agency/edit', 'location');
                    break;
                endswitch;
                break;
            case "users":
                switch ($which) :
                    case "add":
						$email = 'home:' . $this->input->post('PersonalEmailAddress') . (($this->input->post('WorkEmailAddress')) ? ',work:' . $this->input->post('WorkEmailAddress') : '');
						$first_name = $this->input->post('FirstName');
						$last_name = $this->input->post('LastName');
						$address = 'street:' . $this->input->post('Street') . ',city:' . $this->input->post('City') . ',state:' . $this->input->post('State') . ",zipcode:" . $this->input->post('ZipCode');
						$username = $this->input->post('PersonalEmailAddress');
						$phone = 'main:' . $this->input->post('DirectPhone') . (($this->input->post('MobilePhone')) ? ',mobile:' . $this->input->post('MobilePhone') : '') . (($this->input->post('FaxPhone')) ? ',fax:' . $this->input->post('FaxPhone') : '');
						$accessID = $this->input->post('AccessLevel');
						$status = $this->input->post('Status');
						$generated_password = 'dom123456';
						$version = NULL;
				
                        //add users
                        $user_generated = 0;
                        $created = date(FULL_MILITARY_DATETIME);
                        
                        $usersTable = array(
                          'USER_Name' => $this->input->post('Username'),
						  'Team' => $this->input->post('team')
                        );
                        
                        $addUser = $this->members->addUsers($usersTable);
                        
						$addUserInfo = FALSE;
						$msg = '';
                        if($addUser) {
                            
                            if($this->user['DropdownDefault']->LevelType == 'a') {
                                $type = 'AID:' . $this->user['DropdownDefault']->SelectedAgency;
								$selected_id = $this->user['DropdownDefault']->SelectedAgency;
                            }elseif($this->user['DropdownDefault']->LevelType == 'g') {
                                $type = 'GID:' . $this->user['DropdownDefault']->SelectedGroup;
								$selected_id = $this->user['DropdownDefault']->SelectedGroup;
                            }else {
                                $type = 'CID:' . $this->user['DropdownDefault']->SelectedClient;
								$selected_id = $this->user['DropdownDefault']->SelectedClient;
                            }
                            
                            $directoryTable = array(                    
                                'Title_ID'              => $this->input->post('Title'),
                                'DIRECTORY_Type'        => $type,
                                'DIRECTORY_FirstName'   => $first_name,
                                'DIRECTORY_LastName'    => $this->input->post('LastName'),
                                'DIRECTORY_Address'     => $address,
                                'DIRECTORY_Email'       => $email,
                                'DIRECTORY_Phone'       => $phone,
                                'DIRECTORY_Notes'       => $this->input->post('Notes'),
                                'DIRECTORY_Created'     => date(FULL_MILITARY_DATETIME)
                            );
                            
                            $addDirectory = $this->members->addDirectory($directoryTable);
                            
                            if($addDirectory) {
                               // print_object($addUser);
                                $userInfoTable = array(
                                    'USER_ID'               => $addUser->ID,
                                    'DIRECTORY_ID'          => $addDirectory->ID,
                                    'CLIENT_ID'             => $selected_id,
                                    'ACCESS_ID'             => $accessID,
                                    'USER_Modules'          => get_user_modules($accessID),
                                    'USER_GravatarEmail'    => 'jwdavis@dealeronlinemarketing.com',
                                    'USER_Password'         => encrypt_password($generated_password),
                                    'USER_Active'           => 1,
                                    'USER_ActiveTS'         => date(FULL_MILITARY_DATETIME),
                                    'USER_Generated'        => 0,
                                    'USER_Created'           => date(FULL_MILITARY_DATETIME),
									'USER_Release'			=> NULL
                                );
								
								$addUserInfo = $this->members->addUserInfo($userInfoTable);
								
                                if($addUserInfo) {
                                    $msg   = 'Dear ' . $first_name . ' ' . $last_name . ', <br>You have been added to the DOM CMS.<br>Your password is ' . $generated_password . ' and your username is ' . $username . '.<br>Go here and login <a href="http://content.dealeronlinemarketing.com">Go To Dashboard</a>';
                                    $email = $this->members->email_results($username, 'You\'ve been added to the DOM CMS!', $msg);
                                }
                           }
                        }
						
						throwError(newError('Users Add',
											($addUserInfo) ? 1 : -1,
											($addUserInfo) ? $msg
														   : 'Something went wrong. Please try again or contact your admin.',
											0, ''));
						redirect('/admin/users','refresh');

                    break;
                    case "edit":
                        //edit users
                        $form = $this->input->post();
						
						if($this->user['DropdownDefault']->LevelType == 'a') {
							$type = 'AID:' . $this->user['DropdownDefault']->SelectedAgency;
							$selected_id = $this->user['DropdownDefault']->SelectedAgency;
						}elseif($this->user['DropdownDefault']->LevelType == 'g') {
							$type = 'GID:' . $this->user['DropdownDefault']->SelectedGroup;
							$selected_id = $this->user['DropdownDefault']->SelectedGroup;
						}else {
							$type = 'CID:' . $this->user['DropdownDefault']->SelectedClient;
							$selected_id = $this->user['DropdownDefault']->SelectedClient;
						}
						
						$email = 'home:' . $this->input->post('PersonalEmailAddress') . 
						  		 (($this->input->post('WorkEmailAddress')) ? 
							     ',work:' . $this->input->post('WorkEmailAddress') : 
						         '');
								 
						$phone = 'main:' . $this->input->post('DirectPhone') . 
								 (($this->input->post('MobilePhone')) ? 
								 ',mobile:' . $this->input->post('MobilePhone') : '') . 
								 (($this->input->post('FaxPhone')) ? 
								 ',fax:' . $this->input->post('FaxPhone') : '');
						
                        $update = array(
							'Users' => array(
                            	'USER_Name' => $form['username'],
								'Team' => $form['team']
							),
							'Directories' => array(
								'DIRECTORY_ID'			=> $form['directory_id'],
                                'TITLE_ID'              => $form['Title'],
                                'DIRECTORY_Type'        => $type,
                                'DIRECTORY_FirstName'   => $form['firstname'],
                                'DIRECTORY_LastName'    => $form['lastname'],
								
                                'DIRECTORY_Address'     => 'street:'   . $form['street'] . 
														   ',city:'    . $form['city'] . 
														   ',state:'   . $form['state'] . 
														   ',zipcode:' . $form['zip'],
														   
                                'DIRECTORY_Email'       => $email,
                                'DIRECTORY_Phone'       => $phone,
                                'DIRECTORY_Notes'       => $form['notes'],
							),
							'Users_Info' => array(
								'USER_ID'               => $form['user_id'],
								'DIRECTORY_ID'          => $form['directory_id'],
								'CLIENT_ID'             => $selected_id,
								'ACCESS_ID'             => $form['permissionlevel'],
								'USER_Modules'          => get_user_modules($form['permissionlevel']),
								'USER_GravatarEmail'    => 'jwdavis@dealeronlinemarketing.com',
								'USER_Active'           => $form['Status'],
								'USER_ActiveTS'         => date(FULL_MILITARY_DATETIME),
								'USER_Generated'        => 0,
							)
                        );
						
						$update = $this->administration->updateUser($update);
						
						throwError(newError('Users Edit',
											($update) ? 1 : -1,
											($update) ? 'Your User was edited successfully!'
													  : 'Something went wrong. Please try again or contact your admin.',
											0, ''));
						redirect('/admin/users','refresh');
                        
                    break;
                endswitch;
           break;
		   
		   case "passwords":
		   		switch ($which) :
					case "add":
					
						$form = $this->input->post();
						
						// Get ID of Type. Add new Type if needed.
						if ($form['radioType'] == 'old')
							$typeID = $form['types'];
						else {
							$typeID = $this->administration->hasPasswordType($form['newType']);
							if (!$typeID) {
								$data = array('PASS_TYPE_Name' => $form['newType']);
								$typeID = $this->administration->addPasswordType($data);
							}
						}
						
						// Get ID of Vendor. Add new Vendor if needed.
						if ($form['radioVendor'] == 'old')
							$vendorID = $form['vendors'];
						else {
							$vendorID = $this->administration->hasVendor($form['newVendor']);
							if (!$vendorID) {
								$data = array('VENDOR_Name' => $form['newVendor']);
								$vendorID = $this->administration->addVendor($data);
							}
						}
						
						// Save information.
						$data = array(
							'PASS_ClientID' => $form['ClientID'],
							'PASS_TypeID' => $typeID,
							'PASS_VendorID' => $vendorID,
							'PASS_LoginAddress' => $form['login_address'],
							'PASS_Username' => $form['username'],
							'PASS_Password' => $form['password'],
							'PASS_Notes' => $form['notes']
						);
						$pwds = $this->administration->addPasswords($data);
						
						// Throw error and redirect back to Passwords.
						if ($pwds)
							throwError(newError("Passwords Add", 1, 'Password successfully added!', 0, ''));
						else
							throwError(newError("Passwords Add", -1, 'Something went wrong. Please try again or contact your admin.', 0, ''));
						
						redirect('passwords','refresh');
					break;
					
					case "edit":
						$form = $this->input->post();
						
						// Get ID of Type. Add new Type if needed.
						if ($form['radioType'] == 'old')
							$typeID = $form['types'];
						else {
							$typeID = $this->administration->hasPasswordType($form['newType']);
							if (!$typeID) {
								$data = array('PASS_TYPE_Name' => $form['newType']);
								$typeID = $this->administration->addPasswordType($data);
							}
						}
						
						// Get ID of Vendor. Add new Vendor if needed.
						if ($form['radioVendor'] == 'old')
							$vendorID = $form['vendors'];
						else {
							$vendorID = $this->administration->hasVendor($form['newVendor']);
							if (!$vendorID) {
								$data = array('VENDOR_Name' => $form['newVendor']);
								$vendorID = $this->administration->addVendor($data);
							}
						}
							
						// Save information.
						$data = array(
							'PASS_TypeID' => $typeID,
							'PASS_VendorID' => $vendorID,
							'PASS_LoginAddress' => $form['login_address'],
							'PASS_Username' => $form['username'],
							'PASS_Password' => $form['password'],
							'PASS_Notes' => $form['notes']
						);
						$pwds = $this->administration->editPassword($data, $form['PasswordsID']);
						
						// Throw error and redirect back to Passwords.
						if ($pwds)
							throwError(newError("Passwords Edit", 1, 'Password successfully updated!', 0, ''));
						else
							throwError(newError("Passwords Edit", -1, 'Something went wrong. Please try again or contact your admin.', 0, ''));
						
						redirect('passwords','refresh');
						
					break;
				endswitch;
		   
		   case "dpr":
		   		switch ($which) :
		   			case "add":
						$form = $this->input->post();
						
						// There may be more then one service posted. Loop through them.
						// Services are 0-indexed.
						for ($s = 0; $s < $form['sourceCount']; $s++) {
							$provider_id = $form['providers'];
							// Pull custom info (use if select value is AddCustom)
							$customProvider = $form['customProvider'];
							$customProviderURL = $form['customProviderURL'];
							
							$service_id = $form['sources_'.$s];
							// Pull custom info (use if select value is AddCustom)
							$customService = $form['customSource_'.$s];
							
							$month = $form['month'];
							$year = $form['year'];
							$date = $year . '/' . $month . '/01';
							$total = $form['total_'.$s];
							$cost = $form['cost'];
							
							$err_msg = '';
							$err_level = 0;
							$err_elements = array();
							// Validate data and alert user if error (if javascript validation isn't working).
							if ($provider_id == '' ||
								($provider_id == 'AddCustom' && ($customProvider == '' || $customProviderURL == '')) ||
								$service_id == '' ||
								($service_id == 'AddCustom' && $customService == '') ||
								$date == '' ||
								$total == '' ||
								$cost == '') {
									$err_msg = 'All fields are required.';
							} else if (!(preg_match('/^[0-9]+|([0-9]+)?\.[0-9]+$/', $total))) {
									$err_msg = 'Total field must be a number.';
							} else if (!(preg_match('/^[0-9]+|([0-9]+)?\.[0-9]+$/', $cost))) {
									$err_msg = 'Cost field must be a number.';
							}
							if ($err_msg != '') {
								$err_level = -1;
								$err_msg .= ' Lead was not saved.';
								// Break out at the first sign of trouble.
								break;
								
							} else {
								// If provider is new..
								if ($provider_id == 'AddCustom') {
									// ..check if exists in database.
									$provider_id = $this->rep->providerID($customProvider);
									// If it doesn't..
									if (!$provider_id) {
										// ..add to database.
										$provider_data = array(
											'provider' => $customProvider,
											'providerURL' => $customProviderURL
										);
										$provider_id = $this->rep->addProvider($provider_data);
									}
								}
								// If service is new..
								if ($service_id == 'AddCustom') {
									// ..check if exists in database.
									$service_id = $this->rep->serviceID($customService);
									// If it doesn't..
									if (!$service_id) {
										// ..add to database.
										$service_data = array(
											'service' => $customService
										);
										$service_id = $this->rep->addService($service_data);
									}
								}
								
								// Add total to report table.
								$lead_data = array(
									'providerID' => $provider_id,
									'serviceID' => $service_id,
									'month' => $month,
									'year' => $year,
									'date' => $date,
									'total' => $total,
									'cost' => $cost,
									'clientID' => $this->user['DropdownDefault']->SelectedClient
								);
								$this->rep->addLeadTotal($lead_data);
								
								$err_level = 1;
								$err_msg = 'Leads successfully saved.';
							}
						}
						
						// Throw error and redirect back to DPR.
						throwError(newError("DPR Sources", $err_level, $err_msg, 0, ''));
						redirect('dpr','refresh');
					break;
				endswitch;
				
        endswitch;
    }
	public function reset_dd_session($page) {
		$type = $this->user['DropdownDefault']->LevelType;
        switch ($type) {
            case 'a':
				$this->user['DropdownDefault']->LevelType = 'a';
                $this->user['DropdownDefault']->SelectedAgency = 1;
                $this->user['DropdownDefault']->SelectedGroup = NULL;
                $this->user['DropdownDefault']->SelectedClient = NULL;
                break;
            case 'g':
				$this->user['DropdownDefault']->LevelType = 'a';
                $this->user['DropdownDefault']->SelectedGroup  = NULL;
                $this->user['DropdownDefault']->SelectedAgency = $this->administration->getGroupID($selected)->AgencyID;
                $this->user['DropdownDefault']->SelectedClient = NULL;
                break;
            case 'c':
				$this->user['DropdownDefault']->LevelType = 'g';
                $this->user['DropdownDefault']->SelectedClient = NULL;
                $this->user['DropdownDefault']->SelectedGroup = $this->administration->getClientID($selected)->GroupID;
                $this->user['DropdownDefault']->SelectedAgency = $this->administration->getGroupID($selected)->AgencyID;
                break;
        }
		
		$this->session->sess_write();
		redirect($page,'refresh');
	}

}
