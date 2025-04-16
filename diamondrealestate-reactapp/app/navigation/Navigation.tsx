import React from 'react';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import LoginScreen from '../screens/LoginScreen';
import SignupScreen from '../screens/SignupScreen';
import ListingsScreen from '../screens/ListingScreen';
import NewListingScreen from '../screens/NewListingScreen';

export type RootStackParamList = {
  Login: undefined;
  Signup: undefined;
  Listings: undefined;
  NewListing: undefined;
};

const Stack = createNativeStackNavigator<RootStackParamList>();

export default function Navigation() {
  return (
    <Stack.Navigator initialRouteName="Login">
      <Stack.Screen name="Login" component={LoginScreen} />
      <Stack.Screen name="Signup" component={SignupScreen} />
      <Stack.Screen name="Listings" component={ListingsScreen} />
      <Stack.Screen name="NewListing" component={NewListingScreen} />
    </Stack.Navigator>
  );
}
