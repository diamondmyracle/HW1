import React from 'react';
import { View, Text, Button, StyleSheet } from 'react-native';
import { useRouter, useLocalSearchParams } from 'expo-router';

export default function HomeScreen() {
  const router = useRouter();
  const { username } = useLocalSearchParams();

  const handleLogout = () => {
    router.replace('/login');
  };

  return (
    <View style={styles.container}>
      <Text style={styles.welcome}>
        Welcome, {username ? username : 'Guest'}!
      </Text>

      <View style={styles.buttonContainer}>
        <Button
          title="Go to Listings"
          onPress={() => router.push('/listings')}
        />
      </View>

      <View style={styles.buttonContainer}>
        <Button
          title="Create New Listing"
          onPress={() => router.push('/new-listing')}
        />
      </View>

      <View style={styles.buttonContainer}>
        <Button title="Logout" onPress={handleLogout} color="crimson" />
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    padding: 20,
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  welcome: {
    fontSize: 22,
    marginBottom: 30,
  },
  buttonContainer: {
    marginVertical: 10,
    width: '80%',
  },
});

