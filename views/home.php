<?php
$_SESSION["loginUser"]="trainer";
require_once $_SERVER['DOCUMENT_ROOT'].'/framework/Model.php';

include("views/inc/_header.php");
include("views/inc/_carousel.php");
?>
<section id="show-major">
	<div class="container">
		<div class="row">			
			<?php if (!empty($data['top'])): ?>
			<?php $size = count($data['top']) ?>
			<h3 class="title-top">The top <?php echo $size ?> most popular majors</h3>
				<?php foreach ($data['top'] as $major): ?>
				<div class="col-lg-<?php echo 12/$size?>">
			        <img class="img-circle" src="<?php echo  "http://" . $_SERVER['HTTP_HOST'] ."/upload/major/" . $major['image'] ?>" alt="<?php echo $major['name'] ?>" width="140" height="140">
			        <h2><?php echo $major['name'] ?></h2>
			        <p><?php echo $major['description'] ?></p>
			    </div>		
				<?php endforeach ?>
			<?php endif ?>
		    
		   
		</div>
	</div>
</section>
<section id="intro-enclave" class="block-gray">
	<div class="container">
		<div class="content-text">
			<h3>Welcome to Enclave:10</h3>
			<p>Enclave:10, a company of and by software engineering professionals. We have been providing outstanding quality for software engineering and software testing services since 2007. Basing on demanding features collecting from many big names in IT and ITO industries, we – by ourselves – have created innovative working environment and effective solutions that are now available to all-sized companies.</p>
			<p>With a pool of experienced IT engineers in developing, designing, and SQA engineering, we reliably offer clients the value-added solutions, professional skills, accountability, and industrial domain knowledge to reduce operating costs, eliminate risks, deliver solutions on time and on budget as well as ensure the right decision has been made as our best solutions and services.</p>
			<p>Our expertise in developing applications and supporting professional testing services provided us the knowledge to develop solutions that meet worldwide clients’ expectations nowadays. These solutions do not only help increase productivity, reduce the operating costs, and increase customer satisfaction, but also create sustainable cooperation and development. We are not providing Business Process Outsourcing (BPO) and Initial Public Offering (IPO) either; this helps us centralize our strengths and resources in software outsourcing to satisfy any client needs.</p>
			<p>Moreover, we recently have been recognized as one of the top software companies of 100 Outstanding Enterprises of Danang city in 2014 by the Danang People’s Committee for our outstanding performance, active contribution to local community, and considerable contribution to municipal budget. We are also one of the best partners of Danang University in providing professional courses in software engineering and annual job opportunities for senior students.</p>
		</div>		
		<div class="content-map">
			<img src="assets/img/Vietnam-map-01.png" alt="">
			<h3 style="font-size: 13px;">Ask us about Enclave:10's venues and capabilities suitable to your engagement needs.</p>
		</div>		
	</div>
</section>

<?php include("views/inc/_footer.php");?>
