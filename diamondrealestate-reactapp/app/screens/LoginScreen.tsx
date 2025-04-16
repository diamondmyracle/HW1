import React, { useState } from 'react';
import { View, Text, TextInput, Button, Alert, StyleSheet } from 'react-native';
import { useRouter } from 'expo-router';
import { loginUser } from '../utils/api'; // adjust import if needed

export default function LoginScreen() {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const router = useRouter();

  const handleLogin = async () => {
    if (!username || !password) {
      Alert.alert('Missing credentials');
      return;
    }
  
    try {
      const res = await loginUser(username, password);
      console.log('Login result:', res);
  
      if (res.status === 'success') {
        router.replace({ pathname: '/', params: { username } });
      } else {
        Alert.alert('Login failed', res.message || 'Try again.');
      }      
    } catch (error) {
      console.error(error);
      Alert.alert('Error', 'Something went wrong during login.');
    }
  };
  

  return (
    <View style={styles.container}>
      <Text style={styles.header}>Login</Text>
      <TextInput placeholder="Username" value={username} onChangeText={setUsername} style={styles.input} />
      <TextInput placeholder="Password" value={password} onChangeText={setPassword} secureTextEntry style={styles.input} />
      <Button title="Login" onPress={handleLogin} />
      <Button title="Go to Signup" onPress={() => router.push('/signup')} /> {/* ✅ This is the fix */}
    </View>
  );
}

const styles = StyleSheet.create({
  container: { padding: 20 },
  header: { fontSize: 24, marginBottom: 20 },
  input: { borderWidth: 1, marginBottom: 10, padding: 10 },
});

