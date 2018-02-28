                    {*<!--END OF CONTENT-->*}
                    </div>
                </div>
            </div>{*<!--END OF CONTAINER FOR CONTENT-->*}
        </div>{*<!--END OF CENTRAL CONTAINER FOR CONTENT-->*}

        <hr>
     
        <div class="block">{*<!--FOOTER-->*}
            <div class="column span-5">
                <div align="left">
                    {if isset($smarty.session.userName)}<b>{$smarty.session.usercount}</b> χρήστες<br><b>{$smarty.session.tmimacount}</b> τμήματα{else}&nbsp;{/if}
                </div>
            </div>
            <div class="column span-14">
                <div align="center">
                    <!--
                	<div style=' float: left'><span style='color:red; '><b>ΝΕΑ VERSION</b></span></div>
                    <div style=' float: right'><span style='color:red;'><b>ΝΕΑ VERSION</b></span></div>
                    -->
                    {if isset($smarty.session.donate_request)}<h4>Στηρίζω τη Διαχείριση Απουσιών<br><a href='support.php'>Μάθετε πως ...</a></h4>{else}&nbsp;{/if}
                </div>
            </div>
            <div class="column span-4 last">
                <div align="right">
                    <a href="mailto:infoapousies.gr?subject=Διαχείριση Απουσιών" >GΘ2018</a>
                </div>
            </div>{*<!--END OF FOOTER-->*}
    </div>{*<!--END OF BODY MAIN BLOCK-->*}
</div>{*<!--END OF BODY MAIN CONTAINER-->*}

</body>
</html>
