{* {if $loggedIn && $profile->userCookiePreferenceEssential == 1} *}
    {* <script>
        cookieValues = {
            Essential: {$profile->userCookiePreferenceEssential},
            Analytics: {$profile->userCookiePreferenceAnalytics},
            UserAxis360: {$profile->userCookiePreferenceAxis360},
            UserEbscoEds: {$profile->userCookiePreferenceEbscoEds},
            UserEbscoHost: {$profile->userCookiePreferenceEbscoHost},
            UserSummon: {$profile->userCookiePreferenceSummon},
            UserEvents: {$profile->userCookiePreferenceEvents},
            UserHoopla: {$profile->userCookiePreferenceHoopla},
            UserOpenArchives: {$profile->userCookiePreferenceOpenArchives},
            UserOverdrive: {$profile->userCookiePreferenceOverdrive},
            UserPalaceProject: {$profile->userCookiePreferencePalaceProject},    
            UserSideLoad: {$profile->userCookiePreferenceSideLoad},        
        };
        AspenDiscovery.CookieConsent.fetchUserCookie(encodeURIComponent(JSON.stringify(cookieValues)));
    </script> *}
{* {elseif (empty($smarty.cookies.cookieConsent) || !strstr($smarty.cookies.cookieConsent,'Essential'))}  *}
    <div class="stripPopup">
        <div class="cookieContainer">
            <div class="contentWrap">
                <span>{translate text="We use cookies on this site to enhance your user experience." isPublicFacing=true}</span>
                <abbr>{translate text="For details about the cookies and technologies we use, see our <abbr style='display:inline-block'><u style='cursor:pointer;' onclick='AspenDiscovery.CookieConsent.cookieDisagree();'>cookie policy</u></abbr>. <br/> Using this banner will set a cookie on your device to remember your preferences." isPublicFacing=true}<abbr>
            </div>
            <div class="btnWrap">
                <a onclick="AspenDiscovery.CookieConsent.cookieAgree('all');" href="#" id="consentAgree" class="button">{translate text="Accept all cookies" isPublicFacing=true}</a>
                <a onclick="AspenDiscovery.CookieConsent.cookieAgree('essential');" href="#" id="consentDisagree" class="button">{translate text="Only accept essential cookies" isPublicFacing=true}</a>
                <a onclick="AspenDiscovery.CookieConsent.cookieManage();" href="#" id="consentManage" class="button">{translate text="Manage Cookies" isPublicFacing=true}</a>
            </div>
        </div>
    </div>
