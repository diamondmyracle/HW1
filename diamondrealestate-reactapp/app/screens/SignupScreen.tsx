import React, { useState } from 'react';
import { View, Text, TextInput, Button, Alert, StyleSheet } from 'react-native';
import { useRouter } from 'expo-router';
import { signupUser } from '../utils/api';

export default function SignupScreen() {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [repeatPassword, setRepeatPassword] = useState('');
  const router = useRouter();

  const handleSignup = async () => {
    if (!username || !password || !repeatPassword) {
      Alert.alert('Error', 'All fields are required');
      return;
    }

    if (password !== repeatPassword) {
      Alert.alert('Error', 'Passwords do not match');
      return;
    }

    try {
        const res = await signupUser(username, password);
        console.log('Signup response:', res);
      
        if (res.status === 'success') {
          Alert.alert('Signup successful', res.message);
          router.replace({ pathname: '/', params: { username } }); // go to index with username
        } else {
          Alert.alert('Signup failed', res.message || 'Please try again.');
        }
      } catch (error) {
        console.error(error);
        Alert.alert('Error', 'Something went wrong during signup.');
      }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.header}>Create an Account</Text>
      <TextInput
        placeholder="Username"
        value={username}
        onChangeText={setUsername}
        autoCapitalize="none"
        style={styles.input}
      />
      <TextInput
        placeholder="Password"
        value={password}
        onChangeText={setPassword}
        secureTextEntry
        style={styles.input}
      />
      <TextInput
        placeholder="Repeat Password"
        value={repeatPassword}
        onChangeText={setRepeatPassword}
        secureTextEntry
        style={styles.input}
      />
      <Button title="Sign Up" onPress={handleSignup} />
      <View style={{ marginTop: 20 }}>
        <Button title="Back to Login" onPress={() => router.push('/login')} />
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { padding: 20, flex: 1, justifyContent: 'center' },
  header: { fontSize: 24, marginBottom: 20, textAlign: 'center' },
  input: {
    borderWidth: 1,
    borderColor: '#ccc',
    marginBottom: 12,
    padding: 10,
    borderRadius: 5,
  },
});

