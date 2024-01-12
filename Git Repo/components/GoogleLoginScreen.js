import React, { useState, useEffect } from "react";
import { View, Text } from "react-native";
import { useDispatch, useSelector } from "react-redux";
import LoginScreen from "@src/containers/Custom/LoginScreen";
import {
  GoogleSignin,
  GoogleSigninButton,
} from "@react-native-google-signin/google-signin";

const GoogleLoginScreen = (props) => {
  const dispatch = useDispatch();
  const [isSigningIn, setIsSigningIn] = useState(false);
  const doGoogleSignIn = async () => {
    dispatch({
      type: "GOOGLE_LOGIN_REQUESTED",
      tokenUrl: "/wp-json/bbapp-g-login-addon/v1/g-login",
      errorCallBack: (error) => {
        console.log("Google Login Req ERROR:" + error);
      },
    });
  };

  const errorCallBack = (error) => {
    console.log("Error Callback:", error);
  };

  const GerrorCallBack = (error) => {
    console.log("Google Sign-In Error Callback:", error);
  };

  return (
    <>
      <View style={{ flex: 0.8 }}>
        <LoginScreen hideTitle={true} {...props} />
      </View>
      <View
        style={{
          flex: 0.2,
          alignItems: "center",
          justifyContent: "center",
          backgroundColor: "#36454d", //Change the Background color of the Signup Rectangle here
        }}
      >
        <Text style={{ color: "#ffffff", marginBottom: 20 }}>
          {" "}
          {/* Change the Background color of the OR text here. */} ---- OR ----
        </Text>
        <GoogleSigninButton
          style={{ width: 290, height: 48 }}
          size={GoogleSigninButton.Size.Wide}
          color={GoogleSigninButton.Color.Dark}
          onPress={doGoogleSignIn}
          disabled={isSigningIn}
        />
      </View>
    </>
  );
};

// Set navigation options to hide the header
GoogleLoginScreen.navigationOptions = () => ({ header: null });

export default GoogleLoginScreen;
