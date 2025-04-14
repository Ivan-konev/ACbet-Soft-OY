<?php
include_once "include/header.php";

//check if logged in 
if(!$user_obj->checkLoginStatus($_SESSION['user'] ['id'])){
	header("Location: login.php");
}

print_r($_SESSION['user'] ['role']);
$result = $user_obj->checkUserRole($_SESSION['user'] ['role'],  300);

print_r($result)
?>








































<?php
/*
Development/Design/Armament
The Alicorn is a massive nuclear-powered submarine capable of aircraft operations. The design for such a submarine started when the Union of Yuktobanian Republics initiated "Project Alicorn", which was to make a submarine that combined the capabilities of the previous Scinfaxi and Hrimfaxi submersible aircraft carriers. While its construction period was unknown, a 600-700 metre docking facility was discovered by the Osean Intelligence Agency alongside two 400 metre docks to repair and maintain the Scinfaxi and Hrimfaxi located under the Yuktobanian city of Okchabursk. Undisturbed despite the potential development, most of the Alicorn's hull had been finished by 2012. Though, as a symbol of disarmament, Yuktobania and the Osean Federation had decided that, after the Circum-Pacific War, it would be best to scrap the Alicorn as a symbol of disarmament. During the same year, it had been noted that Leasath, Estovakia and Erusea were potential candidates to purchase the submarine. In the end, the Kingdom of Erusea purchased the Alicorn as scrap metal from Yuktobania via a private company known as General Resource Trading, then refurbished it into working condition. In design, the Alicorn's hull was that of an trimaran design - amidships, the vessel divides into three stern sections: the central runway and two large auxiliary hulls used for propulsion that contain its weaponry. With a hull length of 495 meters and a beam length of 116 meters, this is the biggest submarine to have ever been manufactured in Strangereal. The two nuclear reactors located under the runway powers the entire vessel. In the case of an accident, the Alicorn can generate oxygen underwater to ensure a substantially higher chance of survival. As an aircraft carrier, the Alicorn's runway allowed for the use of carrier-based aircraft, being able to carry between 20-30 aircraft, ranging from the Sukhoi Su-33, Boeing/McDonnell Douglas F/A-18Fs, Mikoyan MiG-29Ks, and the Dassault Rafale M. Additionally, 8 large bays on each side of the superstructure house launch ports for Submarine-launched UAVs and defensive barrier drones, which can be used even underwater. The barrier drones are reportedly able to block virtually any projectile that would damage the Alicorn. For its armament, the Alicorn was heavily armed with highly lethal weaponry, to which those, including the entire ship itself, was reported as a potential violation of the START2 (Strategic Arms Reduction Treaty). On the side hulls, 48 Vertical Launch System missile launchers are placed within both hulls, supporting various short-range missiles, or Submarine-launched ballistic missiles that included 200 kiloton nuclear warheads. While it has its missiles, the Alicorn has been equipped with its own defensive armaments for anti-aircraft combat, mostly comprising of CIWS turrets and RAM (Rolling Airframe Missle) launchers deployed from hatches along its hull. The main armament of focus for the Alicorn is its two electromagnetic railguns, one on each auxilliary hull. Each of the railguns have a firerate of 80 rounds/minute, firing 200mm sabot rounds (with 155mm projectiles) ranging from Armor-Piercing Composite Rigid (APCR) shells to High-Explosive Anti-Ship (HEAS) shells, guided by GPS/INS. Most notably, on the center of its main hull, the Alicorn is equipped with a 600mm/128 caliber rail cannon hidden underneath the carrier deck used for long-range attacks. With an estimated output of 500 Megajoules, the Alicorn can attack targets as far as 3,000 kilometers away (1,900 miles), to which its Submarine-launched UAVs can be used for terminal guidance for such long-range attacks. The rail cannon can fire discarding sabots armed with tactical neutron shells, bearing a 1kt warhead capable of instantly killing anyone within a 400 metre radius instantly. Additionally, an Electric Countermeasure jammer was also installed to make attacks more difficult towards the Alicorn.


Operational Service/Destruction
Following its refurbishment, the Alicorn was not launched until January 1, 2015, to which it was captained by Matias Torres, the former captain of the Erusean battleship Tanager previously sunk on November 2004 during the Continental War to which he had been dubbed "The Hero of Comberth Harbor" for saving as many of his crew members as possible before the Tanager sank. By December 2, 2015, he was reassigned as the head of the submarine's pre-commissioning crew. However, while the Alicorn was sailing for sea trials on October 9, 2016, it accidentally ran aground underwater on November 10 and only resurfaced two years on October 2018, having remained on the seafloor for 698 days. While some of its crew did not survive, most of the Alicorn's crew, including Captain Torres, survived. Almost a month later on November 2018, the Alicorn was transferred to the Erusean Navy's reserve fleet on November 3, 2018, and Captain Torres was again reassigned as the head of the pre-commissioning crew. The Alicorn didn't see any combat and remained in reserve until a major part of the Erusean Navy had been wiped out by Osean forces during a skirmish, where the Alicorn was quickly moved to active duty. Docked at Artiglio Port, Usea (at the time under Osean Control), Osean forces, including the Long Range Strategic Strike Group (LRSSG), attempted to capture the submarine with Erusean forces attempting to defend their submarine. However, as the Osean forces quickly overwhelmed the Erusean Air Force, high command officials ordered Captain Torres to scuttle the submarine, only for the Captain to suddenly defect, declaring the Alicorn to no longer be under the jurisdiction of the Erusean military. As such, the Alicorn set sail, wiping out the Osean fleet sent to capture it with no issue. Having sent four Rafale Ms of the SACS, an elite squadron dedicated to the Alicorn as a distraction for the Osean Air Force, the Alicorn dived underwater and fled the port. Having gone rouge, Captain Torres wished to use the Alicorn to inflict massive civillian casualties in Oured, the capital city of Osea, in order to horrify both the Osean Federation and the Kingdom of Erusea into ending the Lighthouse War, which he believed that the loss of one million lives would make them "let go of their weapons that would have taken the lives of ten million". 6 days after its defection, the Alicorn utilized its SLUAVs to fire its Rail Cannon shells towards the Osean LRSSG while they were raiding Anchorhead Bay, Erusea, causing one of their fighters to retreat due to damage. 5 hours later, as the LRSSG had left Anchorhead Bay, the Alicorn entered the bay without having to face any resistance as the LRSSG had practically wiped out the Erusean forces stationed there. Resupplying itself with two nuclear shells, the Alicorn then left the area within 10 minutes. By September 14th, the Alicorn arrived in the Spring Sea near the Azalea Seamount. The Alicorn's plan was to fire on the capital city of Oured on September 19th, the 15 year anniversary of the end of the Usean Continental War in order to maximize civillian casualties. However, the LRSSG took notice of this, and commenced Operation Fisherman in order to stop the Alicorn once and for all. With the help of four Osean ships and Anti-submarine aircraft, the Alicorn, despite having sent SLUAVs and its Rafales, was found and forced to surface after getting hit by ASROCs. An extremely lengthy battle ensued, with two Osean ships sinking due to the Alicorn's railguns, but the Alicorn, despite being armed to the teeth and utilizing barrier drones to block attacks, was severely damaged by LRSSG aircraft, losing most of its weaponry and having its ballast tanks blown out and thus preventing the submarine to submerge. Captain Torres suddenly called a surrender towards the Osean LRSSG, forcing the LRSSG's aircraft to disengage as attacking a surrendering ship would have violated the International Law. However, in an act of deception, the "surrender" was only to buy time for the Alicorn to deploy its rail cannon and target the Osean capital of Oured. One of the LRSSG aircraft, notably bearing three strikes on its tail, was quick to weave past the barrier drones deployed and landed a hit on the rail cannon, causing the fire control system to malfunction as it fired and thus, causing the shot to miss its mark. It is highly likely the shell that missed landed at open waters and caused no damage. Despite its foiled attack, Captain Torres ordered for the next shell to be loaded. However, due to the earlier attack that disabled the Alicorn's FCS, the elevation of the rail cannon was unable to be altered. In a desperate attempt, Captain Torres flooded the Alicorn's aft trim tanks, tipping the entire submarine backwards to give the rail cannon the elevation it needed to hit home. Before it was able to fire again, the LRSSG's aircraft with the three strikes struck a blow on the rail cannon's base. As a result, the rail cannon overloaded and caused an explosive chain reaction across the Alicorn and shortly after, a massive explosion that split the Alicorn in half and killed everyone onboard. The submarine's bisected halves sank beneath the waves before exploding violently, destroying the Alicorn once and for all.

*/
?>