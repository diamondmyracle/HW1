import React, { useState } from 'react';
import { View, TextInput, Button, Alert, StyleSheet } from 'react-native';
import { useRouter } from 'expo-router';
import { createListing } from '../utils/api';

export default function NewListingScreen() {
  const [name, setName] = useState('');
  const [des, setDes] = useState('');
  const [price, setPrice] = useState('');
  const router = useRouter();

  const handleSubmit = async () => {
    const payload = {
      listname: name,
      listdes: des,
      listprice: parseFloat(price),
    };
    const res = await createListing(payload);
    if (res.success) {
      Alert.alert('Success', 'Listing created!');
      router.push('/listings');
    } else {
      Alert.alert('Failed', res.message || 'Try again.');
    }
  };

  return (
    <View style={styles.container}>
      <TextInput placeholder="Name" value={name} onChangeText={setName} style={styles.input} />
      <TextInput placeholder="Description" value={des} onChangeText={setDes} style={styles.input} />
      <TextInput placeholder="Price" value={price} onChangeText={setPrice} keyboardType="numeric" style={styles.input} />
      <Button title="Submit Listing" onPress={handleSubmit} />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { padding: 20 },
  input: { borderWidth: 1, marginBottom: 10, padding: 10 },
});