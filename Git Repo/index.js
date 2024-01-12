import GoogleLoginScreen from "./components/GoogleLoginScreen";
export const applyCustomCode = (externalCodeSetup) => {
  externalCodeSetup.navigationApi.replaceScreenComponent(
    "LoginScreen",
    GoogleLoginScreen
  );
};
