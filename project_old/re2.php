<?
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment; filename=report-re1.doc");
?>

<?

    include("conn.php"); 
	include("fucntion.php"); 
 
    $date_now= date('Y-m-d');
	
	$sql = "SELECT * FROM fee_research  where term='$_GET[term]' 
	           and year='$_GET[year]'  and std_code='$_GET[std_code]' ";
	$result=mysql_query($sql);
	//echo $sql;
	$data = mysql_fetch_array($result);		   
   

?>
<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-874">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 12">
<meta name=Originator content="Microsoft Word 12">
<link rel=File-List href="re2.files/filelist.xml">
<link rel=Edit-Time-Data href="re2.files/editdata.mso">
<!--[if !mso]>
<style>
v\:* {behavior:url(#default#VML);}
o\:* {behavior:url(#default#VML);}
w\:* {behavior:url(#default#VML);}
.shape {behavior:url(#default#VML);}
</style>
<![endif]--><!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:Author>Teeranoot</o:Author>
  <o:LastAuthor>adminyoo</o:LastAuthor>
  <o:Revision>2</o:Revision>
  <o:TotalTime>12</o:TotalTime>
  <o:LastPrinted>2014-07-21T07:03:00Z</o:LastPrinted>
  <o:Created>2014-07-23T07:52:00Z</o:Created>
  <o:LastSaved>2014-07-23T07:52:00Z</o:LastSaved>
  <o:Pages>1</o:Pages>
  <o:Words>220</o:Words>
  <o:Characters>1257</o:Characters>
  <o:Company>KhonKaen University</o:Company>
  <o:Lines>10</o:Lines>
  <o:Paragraphs>2</o:Paragraphs>
  <o:CharactersWithSpaces>1475</o:CharactersWithSpaces>
  <o:Version>12.00</o:Version>
 </o:DocumentProperties>
</xml><![endif]-->
<link rel=dataStoreItem href="re2.files/item0001.xml"
target="re2.files/props0002.xml">
<link rel=themeData href="re2.files/themedata.thmx">
<link rel=colorSchemeMapping href="re2.files/colorschememapping.xml">
<!--[if gte mso 9]><xml>
 <w:WordDocument>
  <w:View>Print</w:View>
  <w:Zoom>110</w:Zoom>
  <w:SpellingState>Clean</w:SpellingState>
  <w:GrammarState>Clean</w:GrammarState>
  <w:TrackMoves>false</w:TrackMoves>
  <w:TrackFormatting/>
  <w:PunctuationKerning/>
  <w:ValidateAgainstSchemas/>
  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
  <w:DoNotPromoteQF/>
  <w:LidThemeOther>EN-US</w:LidThemeOther>
  <w:LidThemeAsian>X-NONE</w:LidThemeAsian>
  <w:LidThemeComplexScript>TH</w:LidThemeComplexScript>
  <w:Compatibility>
   <w:BreakWrappedTables/>
   <w:SnapToGridInCell/>
   <w:ApplyBreakingRules/>
   <w:WrapTextWithPunct/>
   <w:UseAsianBreakRules/>
   <w:DontGrowAutofit/>
   <w:SplitPgBreakAndParaMark/>
   <w:DontVertAlignCellWithSp/>
   <w:DontBreakConstrainedForcedTables/>
   <w:DontVertAlignInTxbx/>
   <w:Word11KerningPairs/>
   <w:CachedColBalance/>
  </w:Compatibility>
  <w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel>
  <m:mathPr>
   <m:mathFont m:val="Cambria Math"/>
   <m:brkBin m:val="before"/>
   <m:brkBinSub m:val="&#45;-"/>
   <m:smallFrac m:val="off"/>
   <m:dispDef/>
   <m:lMargin m:val="0"/>
   <m:rMargin m:val="0"/>
   <m:defJc m:val="centerGroup"/>
   <m:wrapIndent m:val="1440"/>
   <m:intLim m:val="subSup"/>
   <m:naryLim m:val="undOvr"/>
  </m:mathPr></w:WordDocument>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <w:LatentStyles DefLockedState="false" DefUnhideWhenUsed="true"
  DefSemiHidden="true" DefQFormat="false" DefPriority="99"
  LatentStyleCount="267">
  <w:LsdException Locked="false" Priority="0" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Normal"/>
  <w:LsdException Locked="false" Priority="9" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="heading 1"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 2"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 3"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 4"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 5"/>
  <w:LsdException Locked="false" Priority="0" QFormat="true" Name="heading 6"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 7"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 8"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 9"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 1"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 2"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 3"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 4"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 5"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 6"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 7"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 8"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 9"/>
  <w:LsdException Locked="false" Priority="35" QFormat="true" Name="caption"/>
  <w:LsdException Locked="false" Priority="10" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Title"/>
  <w:LsdException Locked="false" Priority="1" Name="Default Paragraph Font"/>
  <w:LsdException Locked="false" Priority="11" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Subtitle"/>
  <w:LsdException Locked="false" Priority="22" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Strong"/>
  <w:LsdException Locked="false" Priority="20" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Emphasis"/>
  <w:LsdException Locked="false" Priority="59" SemiHidden="false"
   UnhideWhenUsed="false" Name="Table Grid"/>
  <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Placeholder Text"/>
  <w:LsdException Locked="false" Priority="1" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="No Spacing"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 1"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 1"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 1"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 1"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 1"/>
  <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Revision"/>
  <w:LsdException Locked="false" Priority="34" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="List Paragraph"/>
  <w:LsdException Locked="false" Priority="29" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Quote"/>
  <w:LsdException Locked="false" Priority="30" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Intense Quote"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 1"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 1"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 1"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 1"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 1"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 1"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 2"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 2"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 2"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 2"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 2"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 2"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 2"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 2"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 3"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 3"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 3"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 3"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 3"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 3"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 3"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 3"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 4"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 4"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 4"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 4"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 4"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 4"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 4"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 4"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 5"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 5"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 5"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 5"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 5"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 5"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 5"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 5"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 6"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 6"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 6"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 6"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 6"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 6"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 6"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 6"/>
  <w:LsdException Locked="false" Priority="19" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Subtle Emphasis"/>
  <w:LsdException Locked="false" Priority="21" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Intense Emphasis"/>
  <w:LsdException Locked="false" Priority="31" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Subtle Reference"/>
  <w:LsdException Locked="false" Priority="32" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Intense Reference"/>
  <w:LsdException Locked="false" Priority="33" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Book Title"/>
  <w:LsdException Locked="false" Priority="37" Name="Bibliography"/>
  <w:LsdException Locked="false" Priority="39" QFormat="true" Name="TOC Heading"/>
 </w:LatentStyles>
</xml><![endif]-->
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:"Angsana New";
	panose-1:2 2 6 3 5 4 5 2 3 4;
	mso-font-charset:0;
	mso-generic-font-family:roman;
	mso-font-pitch:variable;
	mso-font-signature:16777219 0 0 0 65537 0;}
@font-face
	{font-family:"Cordia New";
	panose-1:2 11 3 4 2 2 2 2 2 4;
	mso-font-charset:0;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:16777219 0 0 0 65537 0;}
@font-face
	{font-family:"Cambria Math";
	panose-1:2 4 5 3 5 4 6 3 2 4;
	mso-font-charset:1;
	mso-generic-font-family:roman;
	mso-font-format:other;
	mso-font-pitch:variable;
	mso-font-signature:0 0 0 0 0 0;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;
	mso-font-charset:0;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:-1610611985 1073750139 0 0 159 0;}
@font-face
	{font-family:Tahoma;
	panose-1:2 11 6 4 3 5 4 4 2 4;
	mso-font-charset:0;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:1627400839 -2147483648 8 0 66047 0;}
@font-face
	{font-family:CordiaUPC;
	panose-1:2 11 3 4 2 2 2 2 2 4;
	mso-font-charset:0;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:-2130706429 0 0 0 65537 0;}
@font-face
	{font-family:"TH SarabunPSK";
	panose-1:2 11 5 0 4 2 0 2 0 3;
	mso-font-charset:0;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:-1593835409 1342185562 0 0 65923 0;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-parent:"";
	margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"CordiaUPC","sans-serif";
	mso-fareast-font-family:"Times New Roman";}
h6
	{mso-style-noshow:yes;
	mso-style-qformat:yes;
	mso-style-link:"หัวเรื่อง 6 อักขระ";
	mso-style-next:ปกติ;
	margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	page-break-after:avoid;
	mso-outline-level:6;
	font-size:14.0pt;
	font-family:"CordiaUPC","sans-serif";
	mso-fareast-font-family:"Times New Roman";
	font-weight:normal;}
p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
	{mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-link:"ข้อความบอลลูน อักขระ";
	margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:8.0pt;
	mso-bidi-font-size:10.0pt;
	font-family:"Tahoma","sans-serif";
	mso-fareast-font-family:"Times New Roman";
	mso-bidi-font-family:"Angsana New";}
p.MsoNoSpacing, li.MsoNoSpacing, div.MsoNoSpacing
	{mso-style-priority:1;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-parent:"";
	margin:0cm;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:11.0pt;
	mso-bidi-font-size:14.0pt;
	font-family:"Calibri","sans-serif";
	mso-fareast-font-family:Calibri;
	mso-bidi-font-family:"Cordia New";}
p.MsoListParagraph, li.MsoListParagraph, div.MsoListParagraph
	{mso-style-priority:34;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	mso-add-space:auto;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	mso-bidi-font-size:12.5pt;
	font-family:"CordiaUPC","sans-serif";
	mso-fareast-font-family:"Times New Roman";
	mso-bidi-font-family:"Angsana New";}
p.MsoListParagraphCxSpFirst, li.MsoListParagraphCxSpFirst, div.MsoListParagraphCxSpFirst
	{mso-style-priority:34;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-type:export-only;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	mso-add-space:auto;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	mso-bidi-font-size:12.5pt;
	font-family:"CordiaUPC","sans-serif";
	mso-fareast-font-family:"Times New Roman";
	mso-bidi-font-family:"Angsana New";}
p.MsoListParagraphCxSpMiddle, li.MsoListParagraphCxSpMiddle, div.MsoListParagraphCxSpMiddle
	{mso-style-priority:34;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-type:export-only;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	mso-add-space:auto;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	mso-bidi-font-size:12.5pt;
	font-family:"CordiaUPC","sans-serif";
	mso-fareast-font-family:"Times New Roman";
	mso-bidi-font-family:"Angsana New";}
p.MsoListParagraphCxSpLast, li.MsoListParagraphCxSpLast, div.MsoListParagraphCxSpLast
	{mso-style-priority:34;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-type:export-only;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	mso-add-space:auto;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	mso-bidi-font-size:12.5pt;
	font-family:"CordiaUPC","sans-serif";
	mso-fareast-font-family:"Times New Roman";
	mso-bidi-font-family:"Angsana New";}
span.6
	{mso-style-name:"หัวเรื่อง 6 อักขระ";
	mso-style-noshow:yes;
	mso-style-unhide:no;
	mso-style-locked:yes;
	mso-style-link:"หัวเรื่อง 6";
	mso-ansi-font-size:14.0pt;
	font-family:"CordiaUPC","sans-serif";
	mso-ascii-font-family:CordiaUPC;
	mso-fareast-font-family:"Times New Roman";
	mso-hansi-font-family:CordiaUPC;
	mso-bidi-font-family:CordiaUPC;}
span.a
	{mso-style-name:"ข้อความบอลลูน อักขระ";
	mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-unhide:no;
	mso-style-locked:yes;
	mso-style-link:ข้อความบอลลูน;
	mso-ansi-font-size:8.0pt;
	mso-bidi-font-size:10.0pt;
	font-family:"Tahoma","sans-serif";
	mso-ascii-font-family:Tahoma;
	mso-fareast-font-family:"Times New Roman";
	mso-hansi-font-family:Tahoma;
	mso-bidi-font-family:"Angsana New";}
span.apple-converted-space
	{mso-style-name:apple-converted-space;
	mso-style-unhide:no;}
span.SpellE
	{mso-style-name:"";
	mso-spl-e:yes;}
span.GramE
	{mso-style-name:"";
	mso-gram-e:yes;}
.MsoChpDefault
	{mso-style-type:export-only;
	mso-default-props:yes;
	font-size:10.0pt;
	mso-ansi-font-size:10.0pt;
	mso-bidi-font-size:10.0pt;
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:Calibri;
	mso-fareast-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:minor-bidi;}
@page WordSection1
	{size:612.0pt 792.0pt;
	margin:28.1pt 72.0pt 7.2pt 72.0pt;
	mso-header-margin:36.0pt;
	mso-footer-margin:36.0pt;
	mso-paper-source:0;}
div.WordSection1
	{page:WordSection1;}
-->
</style>
<!--[if gte mso 10]>
<style>
 /* Style Definitions */
 table.MsoNormalTable
	{mso-style-name:ตารางปกติ;
	mso-tstyle-rowband-size:0;
	mso-tstyle-colband-size:0;
	mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-qformat:yes;
	mso-style-parent:"";
	mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
	mso-para-margin:0cm;
	mso-para-margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:10.0pt;
	font-family:"Calibri","sans-serif";
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;}
</style>
<![endif]--><!--[if gte mso 9]><xml>
 <o:shapedefaults v:ext="edit" spidmax="4098">
  <o:colormenu v:ext="edit" strokecolor="none"/>
 </o:shapedefaults></xml><![endif]--><!--[if gte mso 9]><xml>
 <o:shapelayout v:ext="edit">
  <o:idmap v:ext="edit" data="1"/>
 </o:shapelayout></xml><![endif]-->
</head>

<body lang=EN-US style='tab-interval:36.0pt'>

<div class=WordSection1>

<p class=MsoNormal align=center style='margin-left:36.0pt;text-align:center;
text-indent:36.0pt'><span style='font-size:16.0pt;font-family:"Times New Roman","serif";
mso-ascii-theme-font:major-bidi;mso-hansi-theme-font:major-bidi;mso-bidi-theme-font:
major-bidi'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal><!--[if gte vml 1]><v:shapetype id="_x0000_t75" coordsize="21600,21600"
 o:spt="75" o:preferrelative="t" path="m@4@5l@4@11@9@11@9@5xe" filled="f"
 stroked="f">
 <v:stroke joinstyle="miter"/>
 <v:formulas>
  <v:f eqn="if lineDrawn pixelLineWidth 0"/>
  <v:f eqn="sum @0 1 0"/>
  <v:f eqn="sum 0 0 @1"/>
  <v:f eqn="prod @2 1 2"/>
  <v:f eqn="prod @3 21600 pixelWidth"/>
  <v:f eqn="prod @3 21600 pixelHeight"/>
  <v:f eqn="sum @0 0 1"/>
  <v:f eqn="prod @6 1 2"/>
  <v:f eqn="prod @7 21600 pixelWidth"/>
  <v:f eqn="sum @8 21600 0"/>
  <v:f eqn="prod @7 21600 pixelHeight"/>
  <v:f eqn="sum @10 21600 0"/>
 </v:formulas>
 <v:path o:extrusionok="f" gradientshapeok="t" o:connecttype="rect"/>
 <o:lock v:ext="edit" aspectratio="t"/>
</v:shapetype><v:shape id="Picture_x0020_1" o:spid="_x0000_s1033" type="#_x0000_t75"
 style='position:absolute;margin-left:208.85pt;margin-top:-34.1pt;width:81pt;
 height:92.25pt;z-index:-1;visibility:visible;mso-wrap-style:square;
 mso-wrap-distance-left:9pt;mso-wrap-distance-top:0;mso-wrap-distance-right:9pt;
 mso-wrap-distance-bottom:0;mso-position-horizontal:absolute;
 mso-position-horizontal-relative:text;mso-position-vertical:absolute;
 mso-position-vertical-relative:text' wrapcoords="20400 0 2800 1405 -400 2459 -400 5620 1600 11239 2400 17561 4000 20371 9200 21424 10800 21424 17200 20371 18000 16859 16000 16859 18400 12644 18800 11239 20800 6322 20800 5620 21600 351 21600 0 20400 0">
 <v:imagedata src="https://sc5.kku.ac.th/sci-eoffice/eservice/re2.files/image001.emz" o:title=""/>
 <w:wrap type="tight"/>
</v:shape><![endif]--><![if !vml]><img width=108 height=123
src="https://sc5.kku.ac.th/sci-eoffice/eservice/re2.files/image002.gif" align=left hspace=12 v:shapes="Picture_x0020_1"><![endif]><b><span
style='font-size:16.0pt;font-family:"Times New Roman","serif";mso-ascii-theme-font:
major-bidi;mso-hansi-theme-font:major-bidi;mso-bidi-theme-font:major-bidi'><o:p></o:p></span></b></p>

<p class=MsoNormal><!--[if gte vml 1]><v:shapetype id="_x0000_t202"
 coordsize="21600,21600" o:spt="202" path="m,l,21600r21600,l21600,xe">
 <v:stroke joinstyle="miter"/>
 <v:path gradientshapeok="t" o:connecttype="rect"/>
</v:shapetype><v:shape id="_x0000_s1030" type="#_x0000_t202" style='position:absolute;
 margin-left:351.75pt;margin-top:16.45pt;width:121.4pt;height:68.25pt;
 z-index:1;mso-width-relative:margin;mso-height-relative:margin' stroked="f"/><![endif]--><![if !vml]><span
style='mso-ignore:vglayout'>

<table cellpadding=0 cellspacing=0 align=left>
 <tr>
  <td width=469 height=22></td>
 </tr>
 <tr>
  <td></td>
  <td width=166 height=95 bgcolor=white style='vertical-align:top;background:
  white'><![endif]><![if !mso]><span style='position:absolute;mso-ignore:vglayout;
  z-index:1'>
  <table cellpadding=0 cellspacing=0 width="100%">
   <tr>
    <td><![endif]>
    <div v:shape="_x0000_s1030" style='padding:3.6pt 7.2pt 3.6pt 7.2pt'
    class=shape>
    <p class=MsoNormal style='margin-right:2.4pt'><span lang=TH
    style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'>คณะวิทยาศาสตร์</span><span
    style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><span
    style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><o:p></o:p></span></p>
    <p class=MsoNormal style='margin-right:2.4pt;tab-stops:361.5pt'><span
    lang=TH style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'>มหาวิทยาลัยขอนแก่น</span><span
    style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><o:p></o:p></span></p>
    <p class=MsoNormal style='margin-right:2.4pt;tab-stops:361.5pt'><span
    lang=TH style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'>จังหวัดขอนแก่น</span><span
    style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><span
    style='mso-spacerun:yes'>&nbsp; </span>40002<o:p></o:p></span></p>
    </div>
    <![if !mso]></td>
   </tr>
  </table>
  </span><![endif]><![if !mso & !vml]>&nbsp;<![endif]><![if !vml]></td>
 </tr>
</table>

</span><![endif]><span style='font-size:9.0pt'><o:p>&nbsp;</o:p></span></p>

<br style='mso-ignore:vglayout' clear=ALL>

<p class=MsoNormal style='margin-right:2.4pt'><b><span lang=TH
style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'>ที่</span></b><b><span
style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><span
style='mso-spacerun:yes'>&nbsp;&nbsp; </span><span class=SpellE><span lang=TH>ศธ</span></span><span
style='mso-spacerun:yes'>&nbsp; </span></span></b><span style='font-size:16.0pt;
font-family:"TH SarabunPSK","sans-serif"'>0514.2.1/ <span style='mso-tab-count:
1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><span lang=TH><span style='mso-tab-count:
2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><o:p></o:p></span></p>

<p class=MsoNormal align=center style='text-align:center'><span
style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal style='margin-right:26.55pt'><span style='font-size:16.0pt;
font-family:"TH SarabunPSK","sans-serif"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal align=center style='margin-right:2.4pt;text-align:center'><span
style='font-size:9.0pt;font-family:"TH SarabunPSK","sans-serif"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal style='margin-right:26.55pt;tab-stops:248.1pt'><span
lang=TH style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><span
style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif";color:red'><span
style='mso-spacerun:yes'>&nbsp;&nbsp; </span></span><span class=GramE><span
style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif";color:black;
mso-themecolor:text1'><? echo dateT($date_now) ;?><span style='mso-spacerun:yes'></span><span
lang=TH></span></span></span><span lang=TH style='font-size:16.0pt;
font-family:"TH SarabunPSK","sans-serif";color:black;mso-themecolor:text1'><span
style='mso-spacerun:yes'>&nbsp; </span><span
style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span
style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif";color:black;
mso-themecolor:text1'><o:p></o:p></span></p>

<p class=MsoNormal><span style='font-size:9.0pt;font-family:"Times New Roman","serif";
mso-ascii-theme-font:major-bidi;mso-hansi-theme-font:major-bidi;mso-bidi-theme-font:
major-bidi'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:-15.6pt;margin-bottom:
0cm;margin-left:42.55pt;margin-bottom:.0001pt;text-indent:-42.55pt'><b><span
lang=TH style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'>เรื่อง</span></b><b><span
style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><span
style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span></b><span
lang=TH style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'>การชำระค่าธรรมเนียมการวิจัย
สำหรับนักศึกษาระดับปริญญา<span style='color:black;mso-themecolor:text1'><? echo $data['level'] ;?></span>
คณะวิทยาศาสตร์ มหาวิทยาลัยขอนแก่น</span><span style='font-size:16.0pt;
font-family:"TH SarabunPSK","sans-serif"'><o:p></o:p></span></p>

<p class=MsoNoSpacing style='margin-left:42.55pt;text-indent:-42.55pt'><b><span
lang=TH style='font-size:9.0pt;font-family:"TH SarabunPSK","sans-serif"'><o:p>&nbsp;</o:p></span></b></p>

<p class=MsoNormal style='margin-left:42.55pt;text-indent:-42.55pt'><b><span
lang=TH style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'>เรียน</span></b><span
style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><span
style='mso-spacerun:yes'>&nbsp;&nbsp; </span><span lang=TH><span
style='mso-spacerun:yes'>&nbsp;</span><span style='mso-tab-count:1'>&nbsp; </span><span
style='color:black;mso-themecolor:text1'>.......................................................................</span></span><span
style='color:red'><o:p></o:p></span></span></p>

<p class=MsoNormal><span lang=TH style='font-size:8.0pt;font-family:"TH SarabunPSK","sans-serif"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal style='tab-stops:70.9pt'><b><span lang=TH style='font-size:
16.0pt;font-family:"TH SarabunPSK","sans-serif"'>สิ่งที่ส่งมาด้วย</span></b><span
lang=TH style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><span
style='mso-tab-count:1'>&nbsp;&nbsp; </span>ประกาศคณะวิทยาศาสตร์ ฉบับที่
27/2553 เรื่อง การเก็บค่าธรรมเนียมวิจัยตามหลักสูตร</span><span
style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><o:p></o:p></span></p>

<p class=MsoNormal style='tab-stops:72.8pt 82.6pt'><span lang=TH
style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><span
style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>ระดับบัณฑิตศึกษา
คณะวิทยาศาสตร์</span><span style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><o:p></o:p></span></p>

<p class=MsoNormal><span style='font-size:8.0pt;font-family:"TH SarabunPSK","sans-serif"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal style='text-align:justify;text-justify:inter-cluster;
tab-stops:70.9pt'><b><span lang=TH style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><span
style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></span></b><span
lang=TH style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><span
style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>ด้วย
<span style='color:black;mso-themecolor:text1;background:white'><? echo $data['name'] ;?></span><span
style='mso-spacerun:yes'>&nbsp; </span>ซึ่งปัจจุบันมีสถานภาพเป็นนักศึกษาระดับปริญญา<span
style='color:black;mso-themecolor:text1'><? echo $data['level'] ;?></span> สาขาวิชา<span
style='color:black;mso-themecolor:text1'><? echo $data['couse'] ;?></span> คณะวิทยาศาสตร์
เป็นผู้ได้รับทุนการศึกษาจาก<span style='color:black;mso-themecolor:text1'>..................................</span>
และจากประกาศคณะวิทยาศาสตร์ ฉบับที่ 27/2553<span style='mso-spacerun:yes'>&nbsp;
</span>เรื่อง<span style='mso-spacerun:yes'>&nbsp;
</span>การเก็บค่าธรรมเนียมวิจัยตามหลักสูตรระดับบัณฑิตศึกษา<span
style='mso-spacerun:yes'>&nbsp; </span>คณะวิทยาศาสตร์
เพื่อให้การลงทะเบียนเป็นไปด้วยความเรียบร้อย
นักศึกษาบุคคลดังกล่าวได้ขอให้คณะวิทยาศาสตร์ มหาวิทยาลัยขอนแก่น
แจ้งต่อหน่วยงานต้นสังกัดที่เป็นผู้ให้ทุนการศึกษา
เพื่อทราบรายละเอียดช่องทางการชำระเงินค่าธรรมเนียมวิจัยฯ โดยสามารถชำระเป็น<u>เงินสด</u>
หรือ<u>โอนเงิน<span style='letter-spacing:-.1pt'>เข้าบัญชีธนาคารไทยพาณิชย์ เงินรายได้ <span
class=SpellE>มข.</span>(คณะวิทยาศาสตร์) บัญชีประเภทกระแสรายวัน เลขที่<span
style='mso-spacerun:yes'>&nbsp; </span>551-3-02644-8</span></u>
ซึ่งหลังจากหน่วยงานต้นสังกัดที่เป็นผู้ให้ทุนการศึกษาชำระเงินค่าธรรมเนียมวิจัยฯ
แล้ว นักศึกษาต้องแจ้งข้อมูลพร้อมแสดงหลักฐานการชำระเงินค่าธรรมเนียมวิจัยฯ
ต่อคณะวิทยาศาสตร์ มหาวิทยาลัยขอนแก่น เป็นประจำทุกภาคการศึกษาอีกด้วย
<o:p></o:p></span></p>

<p class=MsoNormal style='text-align:justify;text-justify:inter-cluster;
tab-stops:70.9pt'><span lang=TH style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><span
style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><o:p></o:p></span></p>

<p class=MsoNormal style='margin-right:19.5pt;text-align:justify;text-justify:
inter-cluster;text-indent:70.9pt'><span lang=TH style='font-size:16.0pt;
font-family:"TH SarabunPSK","sans-serif"'>จึงเรียนมาเพื่อโปรดทราบ<o:p></o:p></span></p>

<p class=MsoNormal style='margin-right:19.5pt;text-align:justify;text-justify:
inter-cluster;text-indent:70.9pt'><span style='font-size:8.0pt;font-family:
"TH SarabunPSK","sans-serif"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal><span style='font-size:16.0pt;font-family:"TH SarabunPSK","sans-serif"'><span
style='mso-tab-count:6'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><span
style='mso-spacerun:yes'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span><span lang=TH>ขอแสดงความนับถือ</span><o:p></o:p></span></p>

<p class=MsoNormal><span style='font-size:12.0pt;font-family:"TH SarabunPSK","sans-serif"'><o:p>&nbsp;</o:p></span></p>

<p class=MsoNormal><span style='font-size:12.0pt;font-family:"TH SarabunPSK","sans-serif"'><o:p>&nbsp;</o:p></span></p>

<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style='margin-left:161.3pt;border-collapse:collapse;mso-yfti-tbllook:1184;
 mso-padding-alt:0cm 5.4pt 0cm 5.4pt'>
 <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;height:20.95pt'>
  <td width=419 valign=top style='width:313.95pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:20.95pt'>
  <p class=MsoNoSpacing align=center style='text-align:center;line-height:115%'><span
  lang=TH style='font-size:16.0pt;line-height:115%;font-family:"TH SarabunPSK","sans-serif"'>(ผู้ช่วยศาสตราจารย์เขมิกา<span
  style='mso-spacerun:yes'>&nbsp; </span><span class=SpellE>ลมไธ</span>สง)</span><span
  style='font-size:16.0pt;line-height:115%;font-family:"TH SarabunPSK","sans-serif"'><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style='mso-yfti-irow:1;mso-yfti-lastrow:yes;height:20.95pt'>
  <td width=419 valign=top style='width:313.95pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:20.95pt'>
  <p class=MsoNoSpacing align=center style='text-align:center;line-height:115%'><span
  lang=TH style='font-size:16.0pt;line-height:115%;font-family:"TH SarabunPSK","sans-serif"'>รองคณบดีฝ่ายวิชาการ
  </span><span style='font-size:16.0pt;line-height:115%;font-family:"TH SarabunPSK","sans-serif"'><o:p></o:p></span></p>
  <p class=MsoNoSpacing align=center style='text-align:center;line-height:115%'><span
  lang=TH style='font-size:16.0pt;line-height:115%;font-family:"TH SarabunPSK","sans-serif"'>ปฏิบัติราชการแทนคณบดีคณะวิทยาศาสตร์</span><span
  style='font-size:16.0pt;line-height:115%;font-family:"TH SarabunPSK","sans-serif"'><o:p></o:p></span></p>
  </td>
 </tr>
</table>

<h6><span style='font-size:12.0pt;font-family:"Times New Roman","serif";
mso-ascii-theme-font:major-bidi;mso-hansi-theme-font:major-bidi;mso-bidi-theme-font:
major-bidi'><o:p>&nbsp;</o:p></span></h6>

<h6><span lang=TH style='font-family:"TH SarabunPSK","sans-serif"'>งานบริการการศึกษา
คณะวิทยาศาสตร์ มหาวิทยาลัยขอนแก่น</span><span style='font-family:"TH SarabunPSK","sans-serif"'><o:p></o:p></span></h6>

<p class=MsoNormal><span lang=TH style='font-size:14.0pt;font-family:"TH SarabunPSK","sans-serif"'>โทรสาร</span><span
style='font-size:14.0pt;font-family:"TH SarabunPSK","sans-serif"'><span
style='mso-spacerun:yes'>&nbsp;&nbsp; </span>(043) 202371<span
style='mso-tab-count:1'>&nbsp;&nbsp;&nbsp;&nbsp; </span><span lang=TH><o:p></o:p></span></span></p>

<p class=MsoNormal><span lang=TH style='font-size:14.0pt;font-family:"TH SarabunPSK","sans-serif"'>โทรศัพท์</span><span
style='font-size:14.0pt;font-family:"TH SarabunPSK","sans-serif"'><span
style='mso-spacerun:yes'>&nbsp; </span><span lang=TH>084-6001149</span><span
style='mso-spacerun:yes'>&nbsp; </span><span style='mso-tab-count:2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><span
lang=TH><o:p></o:p></span></span></p>

</div>

</body>

</html>
