import React from 'react';
import { View, Text, Image, StyleSheet } from 'react-native';

export default function ListingCard({ listing }: { listing: any }) {
  return (
    <View style={styles.card}>
      <Text style={styles.title}>{listing.listname}</Text>
      <Text>{listing.listdes}</Text>
      <Text>${listing.listprice}</Text>
      {listing.image && (
        <Image
          source={{ uri: `data:image/jpeg;base64,${listing.image}` }}
          style={{ width: '100%', height: 200, marginTop: 10 }}
          resizeMode="cover"
        />
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  card: { marginBottom: 15, padding: 10, borderWidth: 1, borderRadius: 5 },
  title: { fontSize: 18, fontWeight: 'bold' },
});
