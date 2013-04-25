<?php

	class DOM_Exceptions extends CI_Exceptions {
	
		public function __construct() 
		{
			 parent::__construct();
		}

		// Top-level exceptions handler. Catches all uncaught exceptions
		//  for purpose of processing with error routines below.
		function topExceptionHandler($exception) {
			throwError(newError(
				$exception->getFile(),
				-2,
				$exception->getMessage(),
				array(),
				$exception->getLine(),
				$exception->getTraceAsString()));
		}
	
		/**
		 * 404 Page Not Found Handler
		 *
		 * @access	private
		 * @param	string
		 * @return	string
		 */
		function show_404($page = '')
		{
			$heading = "404 Page Not Found";
			$message = array('Sorry, the page you requested was not found. ');
			
			if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != '')
			{
				// broken link somewhere, so send an email
				// and display the right info to the user.
	
				$referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
				log_message('1', '404 referrer = ' . $referer);
	
				// search engines to look out for, should account for around 98-99% of searches
				// (source: http://www.hitwise.com/us/datacenter/main/dashboard-10133.html )
				$search_engines = array(
					'www.google.',
					'search.yahoo.',
					'bing.',
					'ask.',
					'alltheweb.',
					'altavista.',
					'search.aol',
					'baidu.'
				);
	
				if(strpos($referer, '[your_domain_here]') !== FALSE) // is it a broken internal link?
				{
					$message[0] .= 'It looks like we have a broken link on the site. Please notify admin and we\'ll get it fixed as soon as possible.';
				}
				else
				{				
					$source_text = 'another site';
					
					foreach($search_engines as $search_engine)
					{
						if(strpos($referer, $search_engine) !== FALSE) // bad search engine result?
						{
							$source_text = 'a search engine';						
							break; // no point continuing to loop once we have found a match
						}
					}
					
					$message[0] .= 'It looks like you came from ' . $source_text . ' with a broken link. Please notify admin and we\'ll get it fixed as soon as possible.';
				}
	
				// send email notifying admin of broken link - have to use native function
				// as the CI super-object is not yet instantiated :(
				$to = 'errors@your.domain';
				$subject = 'Simian Studios - Broken inbound link';
				$email_text = 'Broken link at: ' . $_SERVER['HTTP_REFERER'];
				$headers = 'From: system@your.domain' . "\r\n" .
						   'Reply-To: system@your.domain' . "\r\n" .
						   'X-Mailer: PHP/' . phpversion();
	
				mail($to, $subject, $email_text, $headers);
	
			}
			else // no referer, so probably came direct
			{
				$message[0] .= 'It looks like you came directly to this page, either by typing the URL or from a bookmark. Please make sure the address you have typed or bookmarked is correct - if it is, then unfortunately the page is no longer available.';
				$message[] = 'But all is not lost - why not <a href="/about">find out more about us</a>, <a href="/blog">have a read of our blog</a>, or <a href="/portfolio">check out our portfolio</a>? You\'re more than welcome to <a href="/contact">drop us a line</a>, too.';
	
			}
	
			log_message('error', '404 Page Not Found --> '.$page);
			echo $this->show_error($heading, $message, 'error_404', 404);
			exit;
		}
	
	}