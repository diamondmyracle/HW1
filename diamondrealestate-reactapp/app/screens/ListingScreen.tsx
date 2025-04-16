import React, { useEffect, useState } from 'react';
import { View, FlatList, Text, Button, StyleSheet } from 'react-native';
import { fetchListings } from '../utils/api';
import ListingCard from '../components/ListingCard';
import { useRouter } from 'expo-router';

export default function ListingsScreen() {
  const [listings, setListings] = useState<any[]>([]);
  const router = useRouter();

  useEffect(() => {
    const loadListings = async () => {
      const data = await fetchListings();
      setListings(data);
    };
    loadListings();
  }, []);

  return (
    <View style={styles.container}>
      <Button title="Create New Listing" onPress={() => router.push('/new-listing')} />
      <FlatList
        data={listings}
        keyExtractor={(item, index) => index.toString()}
        renderItem={({ item }) => <ListingCard listing={item} />}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 10 },
});
