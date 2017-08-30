page.config {
	# Add TSFE:tmpl|sitetitle as suffix to the pagetitle
	pageTitleFirst = 1

	pageTitleSeparator = –
	pageTitleSeparator.noTrimWrap = | | |

	# override full title with tx_seo_titletag, if available
	pageTitle.override.data = page:tx_seo_titletag
}
