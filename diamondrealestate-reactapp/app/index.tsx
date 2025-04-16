import { View, Text, Button, StyleSheet, Alert } from 'react-native';
import { useRouter } from 'expo-router';
import React from 'react';

export default function HomeScreen() {
  const router = useRouter();

  // MOCK: Replace with real user session from context or storage
  const username = 'User'; // ← Replace with AsyncStorage or global context

  const handleLogout = () => {
    // If using AsyncStorage or context, clear user session here
    Alert.alert('Logged out', 'You have been logged out.');
    router.replace('/login'); // redirect to login
  };

  return (
    <View style={styles.container}>
      <Text style={styles.welcome}>Welcome, {username}!</Text>
      <View style={styles.buttonContainer}>
        <Button title="Go to Listings" onPress={() => router.push('/listings')} />
      </View>
      <View style={styles.buttonContainer}>
        <Button title="Create New Listing" onPress={() => router.push('/new-listing')} />
      </View>
      <View style={styles.buttonContainer}>
        <Button title="Logout" onPress={handleLogout} color="crimson" />
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 20, justifyContent: 'center', alignItems: 'center' },
  welcome: { fontSize: 22, marginBottom: 30 },
  buttonContainer: { marginVertical: 10, width: '80%' },
});
