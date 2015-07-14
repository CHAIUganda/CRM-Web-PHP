package com.brio;

import android.annotation.SuppressLint;
import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.webkit.WebChromeClient;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.ProgressBar;

import com.brio.briowebapp.R;

public class MainActivity extends Activity {

	WebView mWebView;
	private ProgressBar loadingProgressBar;

	@SuppressLint("SetJavaScriptEnabled")
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		requestWindowFeature(Window.FEATURE_NO_TITLE);
		getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN,
				WindowManager.LayoutParams.FLAG_FULLSCREEN);

		setContentView(R.layout.activity_main);

		mWebView = (WebView) findViewById(R.id.webview);

		mWebView.getSettings().setJavaScriptEnabled(true);
		mWebView.setWebViewClient(new DivumWebViewClient());

		mWebView.loadUrl("http://freakpixels.com/portfolio/brio/");

		loadingProgressBar = (ProgressBar) findViewById(R.id.progressbar_title);
		mWebView.setWebChromeClient(new WebChromeClient() {
			@Override
			public void onProgressChanged(WebView view, int newProgress) {
				super.onProgressChanged(view, newProgress);
				loadingProgressBar.setProgress(newProgress);
				if (newProgress == 100) {
					loadingProgressBar.setVisibility(View.GONE);
				} else {
					loadingProgressBar.setVisibility(View.VISIBLE);
				}
			}
		});

	}

	private class DivumWebViewClient extends WebViewClient {
		@Override
		public boolean shouldOverrideUrlLoading(WebView view, String url) {
			view.loadUrl(url);
			return true;
		}
	}
}
