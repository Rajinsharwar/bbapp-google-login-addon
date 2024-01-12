A NON-OFFICIAL addon for the BuddyBoss App, to integrate the One-click Google Register/Login system. This repository consists of a WordPress plugin (Under wordpress-plugin) folder, and the code for use as the custom repo code (Under the Git Repo folder) in BuddyBoss builds. This works with both Android, and iOS, and requires almost zero-code interaction. Using this, you can have your BuddyBoss app, and have the Google login functionality, with no React/coding experience.

![image](https://github.com/Rajinsharwar/bbapp-google-login-addon/assets/68213636/66846b3e-d9f5-4f1e-a5f1-5e489aa1bd89)

Follow me on LinkedIn: https://linkedin.com/in/rajinsharwar
## What do I need to integrate Google Login in my BuddyBoss App?
1. Your BuddyBoss App
2. Developer Access of BuddyBoss.
3. Ability to install a WordPress plugin.

# Table of contents
- [Get Started](#get-started)
- [Configuration](#configuration)
- [Customize](#customize)
- [Disclamer and Support](#get-started)
- [Donate](#donate)

# Get Started:

  - STEP 1: Download this GitHub Repo as a Zip, and extract it.
  - STEP 2: Navigate under the "wordpress-plugin" folder, select the sole file, and compress it into a (.ZIP). Install that ZIP file as a plugin in WordPress.
  - STEP 3: Make sure you have added the custom repo configured in BuddyBoss Account. Navigate under the "Git Repo" folder, copy all the files, and paste into your repo.
    - If you already have your GIT repo configured with custom code, you need to add the code of the files of the "index.js" and "```/components/GoogleLoginScreen.js```".
  - STEP 4: Configure using the below steps.

## Configuration

1. At first, please ensure that Google Firebase setting in the BuddyBoss App is configured properly
   
    ![image](https://github.com/Rajinsharwar/bbapp-google-login-addon/assets/68213636/397aa391-92a5-410a-be3d-9d977889fbcd)
</br>

2. (For Android) To make it work when the app is downloaded from the Play Store, you need to follow some stpes:

    a. In your Google Play console, navigate to Setup > App Integrity > App signing (https://prnt.sc/6uukq5JtAqjE) in your Google Play Console.
   
    ![image](https://github.com/Rajinsharwar/bbapp-google-login-addon/assets/68213636/8011ba72-b952-444b-89f4-219b78e90a86)

    b. Copy the SHA-1 or SHA-256 certificate fingerprint, or both.

    c. Go to your Firebase console > Project Settings > General (https://prnt.sc/Ca0cWsu5RMbu)
   
    ![image](https://github.com/Rajinsharwar/bbapp-google-login-addon/assets/68213636/e56dfaba-7eed-4db2-927e-155a11bef65d)

    d. There, add the SHA certificate fingerprint. Either SHA-1 or SHA-256 will do, or even both.

    e. Make sure you do this, for both the Test App, and the Release App in Firebase.
</br>

3. (For Android) To make it work when the app is downloaded from the BuddyBoss App Builds page:

    a. Go to BuddyBoss App > Configure > Android Settings. (https://prnt.sc/vwRQe9WJ0jeT)
   
    ![image](https://github.com/Rajinsharwar/bbapp-google-login-addon/assets/68213636/a2f43f1f-a07f-4dcf-95cb-6034357b9970)

    b. Generate a keyStore for your app. If you already have a keystore, download the .jks file to your computer. Use this command to generate the SHA certificate fingerprints:

    ```bash
    keytool -list -v -keystore /Users/johnSmith/Downloads/android-keystore.zip.jks -alias app
    ```

    Replace ```/Users/johnSmith/Downloads/android-keystore.zip.jks``` with the file path of the downloaded .jks file.

    c. The command will prompt a password. Use the KeyStore Password you used to generate the KeyStore.

    d. Running the command will generate the SHA certificate fingerprint. If you do not have the “keytool” package installed on your machine, you can try following this external blog post: https://www.misterpki.com/install-keytool/

    e. Copy the SHA1, or the SHA256 and add it to your Firebase console.
</br>

4. Request a new build in BuddyBoss App > Build.

# Customize
You might want to change the color of the background, behind the Google Signin Button in your app. If Yes, navigate under the "```/components/GoogleLoginScreen.js```", and there, around Line 41, you can change the color of the Background.

You can also change the color of the "OR" text to match your background color, from Line 44, in the same file.

# Disclamer and Support
This code or repo is not affiliated with BuddyBoss, so any problems caused due to this won't make BuddyBoss responsible in any way. If you can any issues, feel free to open a new issue in this repo, and I will try to help you.

# Donate
If you feel like donating to me for this project 🙂, you can Donate here => https://www.zeffy.com/en-CA/donation-form/758d32de-0d13-400b-a6ec-ba5f6e03d7b5
