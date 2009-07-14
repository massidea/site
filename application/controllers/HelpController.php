<?php

 
 class HelpController extends Oibs_Controller_CustomController
 {
 	public function init()
    {
        parent::init();
        
        $this->view->title = 'account-title';
    } // end of init()
    
 	public function indexAction()
 	{
 		$data = array();
 		
 		$params = $this->getRequest()->getParams();
        
        $language = $params['language'];
        
       //  echo $username; die;
 		
 		$help = new Models_Help();
 		
 		$data = $help->getAllHelp($language);//->toArray();
 		
 		$this->view->help = $data;
 	}
 	
 	public function viewAction()
 	{
 		echo "testi. vois lollottaa";
 	}
	
	public function aboutAction()
	{
		// get parameters and test what language is used
		$params = $this->getRequest()->getParams();
		
		$language = $params['language'];
		
		if ($language=="fi")
			{
			echo "Yksi kuumimmista talouden ja teknologian muutoksista on Web 2.0 perustuva Internetin sosiaaliset verkot, mikä viittaa nettiyhteisöihin ja ylläpidettyjen palvelu laitosten yhteistyöhön ja jakamiseen käyttäjien kesken. Siksi Avoin innovaatio pankki järjestelmä luotiin tukemaan Suomen kansallista innovaatiojärjestelmää. Järjestelmä on luotu avoimen lähdekoodin päälle, ja tehty tukemaan Googlen avointa API pohjaa ja/tai Facebook-alustaa.";
			?><BR><BR><?php
			echo "Järjestelmä perustuu seuraavan kuvan mukaiseen innovaation kolmio kehyksen päälle. Ylin laatikko on idea pankki. Se sisältää kaksi täydentävää innovaation lähdettä: 1. Tulevaisuuden markkinatalouden pankki jonka teoreettisista perustuksista ja rakenteesta perustuu tulevaisuuden tutkimukselle ja 2. Nykyisien markkinataloudentietopankkiin, mikä on lukittu suuntautumaan asiakas/markkina tutkimuksen piiriin.";
			?><BR><BR><?php
			?><img src="http://www.oibs.fi/wiki/images/d/dd/Innovation_triangle.gif"/><BR><?php
			echo "Näiden pankkien sisältö tuotetaan pääasiassa kahdella vastakkaisilla ryhmillä. Ensimmäinen ryhmä on nuoret (esim. yli sadalla tuhannella oppilaalla, jotka suorittavat alempaa korkeakoulututkintoaan) ja toinen iäkkäät ihmiset (esim. aktiiviset iäkkäämmät yhteisön jäsenet, jotka toimivat yhteiskunnan hyväksi tai tuottamattoman toiminnan organisaatioissa). Integroidaksemme tämän toiminnan Suomen alueellisen innovaatio politiikkaa malliimme, ja luodaksemme tukevan pohjan kanssakäymiselle näiden kolmen pankin välille, jolloin ¨Industry - innovation field matrix¨ (alan innovaatio aluematriisi) on määritelty.";
			?><BR><BR><?php
			echo "Tuloksena mallin odotetaan lisäävän luovan yksilön dynamiikkaa luomalla internet ympäristön, jossa totutut tavat ylitetään helposti. yhteistuotanto ja älykäs sisällön suosittelu lähestymistavat nostavat huomattavasti mahdollisuuksia odottamattomiin löytöihin, jotka ovat suuri innovaation lähde. Pääsääntöisesti olemme integroineet kolmois kierteen ja sosiaalisen verkostoitumisen ideologiat uuteen malliin, jonka väitetään muokkaavan nykyistä käytäntöä yliopistoissa, yritysmaailmassa ja valtion välisissä vuorovaikutuksissa. Tuloksena konseptin ehdotuksesta tuoreet yliopisto-opiskelijat uusine ideoineen ja iäkkäät kansalaiset tärkeällä käytännön tiedollaan voivat tehokkaasti yhdistää voimavaransa avoimeen innovaation perustuvassa sosiaalisessa verkostoitumis yhteisössä.";
			?><BR><BR><?php
			echo "Tähtäämme myös onnistumistarinoiden kehittämiseen, joita voidaan käyttää nostamaan ali-hyödynnettyjä opiskelijoiden ja iäkkäiden kansalaisten luovuutta ja yhteistä vuorovaikutus resursseja pitääksemme Suomen kilpailuedun. Pää-asiakkaat tälle konseptille ovat yritykset, paikalliset auktoriteetit ja paikallinen hallinto. Nämä tekijät pääsevät käsiksi ei pelkästään laajaan ideapankkiin vaan tulevaisuuden markkinoille ympäristöön ja asiakkaiden tarve/ongelma tietoihin, joita päivitetään systemaattisesti. Koska tarjottavan tiedon laatu ja ymmärrettävyys ovat tärkeitä asiakkaille. He ovat valmiita maksamaan tästä pienen palvelumaksun. Kuitenkin, koska käyttö palvelumaksu on muutaman euron luokkaa kaupallista käyttäjää kohti kuukaudessa on mahdollisuus hyötyyn sama sekä pienillä, keskisuurilla että suurilla yrityksillä. Mitä kiinnostavimmin merkittävä osa syntyvistä tuloista jaetaan oppilaiden ja kansalaisten kesken, jotka ovat luoneet alkuperäisen sisällön. Optisimmassa tapahtumasarjassa opintotuen lisäksi, mitkä ovat yleisesti saatavissa yliopisto-oppilaille Suomessa, jonka lisäksi he saisivat maksun suorittaessaan normaaleja opintojaan. Toiselta kädeltä iäkkäät kansalaiset voivat parantaa rahallista statustaan, joka eläke-eksperttien mukaan jää tiedostamatta suurimmalta osalta tulevista eläkeläisistä.";
			}
		else
			{
			echo "One of the hottest economical and technological change driver at the moment seems to be the Web 2.0 based Online Social Networks (later OSN) movement which generally refers to communities and hosted services facilitating collaboration and sharing between users. Therefore in this research project we are proposing a new Open Innovation Bank System (OIBS) for supporting the Finnish national system of innovation (NIS). The system is created to support Google's newly launch OpenSocial APIs and Facebook platform.";
			?><BR><BR><?php
			echo "The OIBS is based on the Innovation Triangle framework (figure 1) which on the top idea bank includes two complementary innovation sources: 1) the future market environment information bank, which theoretical foundations and structure are based on future research and 2) the current market environment information bank, which is grounded on customer/market orientation research domain.";
			?><BR><BR><?php
			?><img src="http://www.oibs.fi/wiki/images/d/dd/Innovation_triangle.gif"/><BR><?php
			echo "The content for these banks will be produced mainly by two main opposite target group: the youth (i.e. over hundred thousand students performing their bachelor's degrees in the universities of applied sciences) and the aged (i.e. active members of ageing people community throughout the network of civic organizations and non-profit associations organizations). In order to integrate the Finnish regional innovation policy in to our model and create the solid interaction interface between the three defined banks, the common content classification schema based on industry - innovation field matrix is defined. In principal the industry dimension is based on the specific regional Centre of Expertise Programme (OSKE) while the innovation field is based on the innovation classifications defined in the literature. This classification will deepen our understating on the produced content profile while allocating the over hundred thousand worker resource efficiently. When the amount of content increases in the web service, one must provide intelligent services to end-users in order to create a solid user experience. As a result the intelligent combination of the following content recommendation approaches will be utilized: user preferences, content or user similarity to other users (i.e. collaboration). Together these individual functional components and the interaction interface between them are forming the overall functionalities, which we named as Open Innovation Bank System (OIBS).";
			?><BR><BR><?php
			echo "As a result our model is expected to increase the dynamics of the individual's creativity by creating an online environment where a conventional habit is easily exceeded. The collaborative content production and intelligent content recommendation approach together will significantly boost the possibilities of unexpected findings which are a major innovation source. In this project we have defined, investigated and implemented a new people-to-people interaction based approach to support the national innovation system in Finland. In principal we have integrated the Triple Helix and the social networking ideologies in to a new model which is argued to change the current practice of university, industry, and government interaction. As a result of our concept suggestion the young university students with fresh ideas and the senior citizens with significant practical knowledge can effectively combine their forces in a open innovation based social networking community. ";
			?><BR><BR><?php
			echo "The main aim in our project is to develop new commercial success stories and uplift the currently under-utilised student and senior citizen creativity and communal interaction resource as a sustaining national competitive advantage for Finland. The main customers for our concept are companies, local authorities and public administration. These actors will have an access not only to the extensive idea bank but also to the future market environment and customer need/problem information, which are systematically updated. Since the quality and the comprehensiveness of the provided information are valuable for our customers, they are willing to pay a small fee for this service. However, since the usage fee level is around few euros per commercial users per month the SMEs will now have the same possibilities to benefit from the service as larger organizations. Most interestingly the significant share of the generated revenues will be distributed to students and senior citizens who have created the original content. In the most optimistic scenario on the top of state study grants, which are generally available for University students in Finland, the students will be well paid when they perform their usual studies. On the other hand the senior citizens can uplift their financial status, which according to pension experts in general will fall into decline for the upcoming pensioners. The aim of the project is also to commercialization of the concept and technological solution by creating a new kind of educational format which can be exported. ";
			?><BR><BR><?php
			echo "From the theoretical point of view the presented Open Innovation Bank System (OIBS) is an open source model for new emerging Online Social Networks. With our research project we have pointed out that OSNs can also play technologically and socially important role in the commercialization process of novel ideas and inventions. OSNs can support commercialization of new ideas, inventions and innovations in large scale. The new OIBS concept includes many interesting characters, both socially and technologically, which are worth to investigate. The researchers will have full access to the data bank export functionalities, which enables the empirical investigations of the produced content. In principal our three databases with extensive classification schema derived from innovation theories are representations of structured text databases as if they were equal to structured interview. In the future we expect that by the support of OSNs we can expect better success rates and wider involvement of social networks to commercialize novel ideas, inventions and innovations. The presented Open Innovation Bank System is one concrete and conceptual framework to implement new kind of open innovation policy in Finland as well as in other countries. ";
			}
	}
 }