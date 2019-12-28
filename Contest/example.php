<?php
$html = file_get_contents("http://moss.stanford.edu/results/706895331");

/*
$html = <<<NOTCODE
<HTML>

<HEAD>
    <TITLE>Moss Results</TITLE>
</HEAD>

<BODY>
    Moss Results<p>
        Fri Nov 29 06:21:46 PST 2019
        <p>
            Options -l cc -m 10
            <HR>
            [ <A HREF="http://moss.stanford.edu/general/format.html" TARGET="_top"> How to Read the Results</A> | <A
                HREF="http://moss.stanford.edu/general/tips.html" TARGET="_top"> Tips</A> | <A
                HREF="http://moss.stanford.edu/general/faq.html"> FAQ</A> | <A
                HREF="mailto:moss-request@cs.stanford.edu">Contact</A> | <A
                HREF="http://moss.stanford.edu/general/scripts.html">Submission Scripts</A> | <A
                HREF="http://moss.stanford.edu/general/credits.html" TARGET="_top"> Credits</A> ]
            <HR>
            <TABLE>
                <TR>
                    <TH>File 1
                    <TH>File 2
                    <TH>Lines Matched
                <TR>
                    <TD><A HREF="http://moss.stanford.edu/results/171621072/match0.html">../Judge/Temporary_Code/21
                            (95%)</A>
                    <TD><A HREF="http://moss.stanford.edu/results/171621072/match0.html">../Judge/Temporary_Code/23
                            (95%)</A>
                    <TD ALIGN=right>6
                <TR>
                    <TD><A HREF="http://moss.stanford.edu/results/171621072/match1.html">../Judge/Temporary_Code/1
                            (95%)</A>
                    <TD><A HREF="http://moss.stanford.edu/results/171621072/match1.html">../Judge/Temporary_Code/23
                            (95%)</A>
                    <TD ALIGN=right>6
                <TR>
                    <TD><A HREF="http://moss.stanford.edu/results/171621072/match2.html">../Judge/Temporary_Code/1
                            (95%)</A>
                    <TD><A HREF="http://moss.stanford.edu/results/171621072/match2.html">../Judge/Temporary_Code/21
                            (95%)</A>
                    <TD ALIGN=right>6
                <TR>
                    <TD><A HREF="http://moss.stanford.edu/results/171621072/match2.html">../Judge/Temporary_Code/22
                            (91%)</A>
                    <TD><A HREF="http://moss.stanford.edu/results/171621072/match2.html">../Judge/Temporary_Code/23
                            (91%)</A>
                    <TD ALIGN=right>6
                <TR>
                    <TD><A HREF="http://moss.stanford.edu/results/171621072/match2.html">../Judge/Temporary_Code/22
                            (55%)</A>
                    <TD><A HREF="http://moss.stanford.edu/results/171621072/match2.html">../Judge/Temporary_Code/24
                            (55%)</A>
                    <TD ALIGN=right>6
            </TABLE>
            <HR>
            Any errors encountered during this query are listed below.<p>
</BODY>

</HTML>
NOTCODE;

//$html = file_get_contents("http://moss.stanford.edu/results/128787015/");
*/

preg_match_all('/<A[^>]*([\s\S]*?)<\/A>/i', $html, $matches);

function get_between($input, $start, $end)
{
    $substr = substr($input, strlen($start) + strpos($input, $start), (strlen($input) - strpos($input, $end)) * (-1));
    return $substr;
}

$num = 1;
foreach ($matches[1] as $iData) {
    if (strstr($iData, '>../Judge/Temporary_Code/')) {
        $data_id = get_between($iData, 'Judge/Temporary_Code/', '(');
        preg_match_all("/(?:\()(.*)(?:\))/i", $iData, $data_sim);

        if ($num % 2 == 1) {
            $first = intval($data_id);
            $sim_first = intval($data_sim[1][0]);
        } else {
            $second = intval($data_id);
            $sim_scond = intval($data_sim[1][0]);

            $runID_Data[$first][$second] = $sim_first;
            echo $first . ' 对比 ' . $second . ' : ' . $sim_first . "<br/> \n";
        }
        $num++;
    }
}
echo '<pre>';
print_r($runID_Data);


/*
include("moss.php");
$userid = 327879735; // Enter your MOSS userid

try {
    $moss = new MOSS($userid);
    $moss->setLanguage('cc');
    //$moss->addFile('../Judge/Temporary_Code/1');
    //$moss->addFile('../Judge/Temporary_Code/21');
    //$moss->addFile('../Judge/Temporary_Code/23');
    $moss->addByWildcard('../Judge/Temporary_Code/*');
    //$moss->addBaseFile('Code/1.c');
    //$moss->setCommentString("This is a test");

    $data = $moss->send();
    //echo 'Code Data: <br/>';
    print_r($data);
} catch (Exception $e) {
    echo 'Code Error: <br/>';
    echo $e->getMessage();
}
*/
