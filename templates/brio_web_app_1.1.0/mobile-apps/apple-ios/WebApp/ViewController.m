//
//  ViewController.m
//  WebApp
//
//  Created by Sourav on 17/11/14.
//  Copyright (c) 2014 Sourav. All rights reserved.
//

#import "ViewController.h"

@interface ViewController ()

@property(nonatomic,weak)IBOutlet UIWebView *webView;

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    
    NSString *fullURL = @"http://freakpixels.com/portfolio/brio/";
    NSURL *url = [NSURL URLWithString:fullURL];
    NSURLRequest *requestObj = [NSURLRequest requestWithURL:url];
    [_webView loadRequest:requestObj];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
